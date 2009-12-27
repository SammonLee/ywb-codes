package Farsail::ActionSet;

use strict; 
use warnings;
use Carp;

use YAML qw/LoadFile/;
use Farsail::Action;
use Farsail::Util qw/expand_file find_file/;
use Scalar::Util qw/blessed/;
use List::MoreUtils qw/uniq/;
use constant GLOBAL_NAMESPACE => 'global';
use Log::Log4perl qw/:easy/;

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = {};
    bless $self, $class;
    $self->{ACTIONS} = {};
    $self->{INCLUDES} = {};
    $self->{ACTIVE_NAMESPACES} = [ GLOBAL_NAMESPACE ];
    $self->{META} = {};
    $self->{DEFAULT_META} = {
        module => 'main',
        auto_detect => 1
    };
    if ( @_ ) {
        my $actions = shift;
        if ( ref $actions eq 'HASH' ) {
            $self->addActions($actions);
        } elsif ( $actions && -e $actions ) {
            $self->setActionsFile($actions);
        }
    }
    return $self;
}

sub addModuleActions {
    my ($self, $module, $defs) = @_;
    foreach my $actions( values %$defs ) {
        foreach ( values %$actions ) {
            $_->{'_meta'} = $module;
        }
    }
    $self->addActions($defs);
}

sub addActions {
    my ($self, $defs) = @_;
    my $include = delete $defs->{include};
    my $actions = $self->{ACTIONS};
    if ( exists $defs->{_meta} && ref $defs->{_meta} eq 'HASH' ) {
        for ( keys %{$defs->{_meta}} ) {
            $self->{DEFAULT_META}{$_} = $defs->{_meta}{$_};
        }
        delete $defs->{_meta};
    }
    foreach my $ns ( keys %$defs ) {
        if ( ref $defs->{$ns} eq 'HASH' ) {
            if ( exists $defs->{$ns}{_meta} ) {
                $self->setMeta($ns, $defs->{$ns}{_meta});
                delete $defs->{$ns}{_meta};
            }
            foreach ( keys %{$defs->{$ns}} ) {
                $actions->{$ns}{$_} = $defs->{$ns}{$_};
            }
        } else {
            ERROR("Wrong action format for $_");
        }
    }
    if ( $include ) {
        my @files;
        if ( ref $include ne 'ARRAY' ) {
            $include = [$include];
        }
        my $dir = $self->getActionsFile() ? $self->getActionsFile()->dir : undef;
        for ( @$include ) {
            my @f = find_file(expand_file($_, $dir));
            if ( @f ) {
                push @files, @f;
            } else {
                ERROR("Can't find action file '$_'");
            }
        }
        foreach ( @files ) {
            $self->loadFile($_);
        }
    }
}

sub setMeta{
    my ($self, $namespace, $meta) = @_;
    for ( qw/module auto_detect/ ) {
        if ( exists $meta->{$_} ) {
            $self->{META}{$namespace}{$_} = $meta->{$_};
        }
    }
    if ( exists $meta->{types} ) {
        my $types = $meta->{types};
        foreach ( keys %$types ) {
            $self->{META}{$namespace}{types}{$_} = $types->{$_};
        }
    }
    return $self;
}

sub getMeta{
    my ($self, $namespace, $name) = @_;
    if ( exists $self->{META}{$namespace}{$name} ) {
        return $self->{META}{$namespace}{$name};
    }
    elsif ( exists $self->{DEFAULT_META}{$name} ) {
        return $self->{DEFAULT_META}{$name};
    }
}

sub setActionsFile {
    my ($self, $file) = @_;
    $file = expand_file($file);
    $self->{ACTIONS_FILE} = $file;
    $self->loadFile($file);
    return $file;
}

sub getActionsFile {
    return shift->{ACTIONS_FILE};
}

sub loadFile {
    my ($self, $file) = @_;
    if ( exists $self->{INCLUDES}{$file} ) {
        return;
    }
    $self->{INCLUDES}{$file}++;
    DEBUG("Load actions '$file'");
    my $actions = eval { LoadFile($file); };
    if ( $@ ) {
        confess "Load action '$file' failed: $@\n";
    }
    $self->addActions($actions);
}

sub hasAction {
    my ($self, $action, $namespace) = @_;
    my (@ns, $action_name);
    if ( blessed($action) && $action->isa('Farsail::Action') ) {
        push @ns, $action->getNamespace();
        $action_name = $action->getName();
    } else {
        if ( index($action, '.') == -1 ) { # action doesn't contain namespace
            @ns = $namespace ? @$namespace : $self->getActiveNamespaces();
            $action_name = $action;
        } else { 
            my @r = split /\./, $action, 2;
            push @ns, $r[0];
            $action_name = $r[1];
        }
    }
    for ( @ns ) {
        if ( exists $self->{ACTIONS}{$_} && exists $self->{ACTIONS}{$_}{$action_name} ) {
            return ($_, $action_name);
        }
    }
    for ( @ns ) {
        if ( $self->getMeta($_, 'auto_detect') ) {
            my $module = $self->getMeta($_, 'module');
            $self->loadModule($module);
            if ( $module->can('ACTION_' . $action_name) ) {
                return ($_, $action_name);
            }
        }
    }
    return;
}

sub getAction {
    my ($self, $action, $namespace) = @_;
    my ($ns, $name) = $self->hasAction($action, $namespace);
    if ( $ns ) {
        if ( blessed($action) && $action->isa('Farsail::Action') ) {
            return $action;
        } else {
            return $self->{ACTIONS}{$ns}{$name} = new Farsail::Action(
                actions => $self,
                namespace => $ns,
                name => $name,
                action => $self->{ACTIONS}{$ns}{$name} || {}
            );
        }
    } else {
        ERROR("Unknown action '$action'");
    }
}

sub getActiveNamespaces {
    return @{shift->{ACTIVE_NAMESPACES}};
}

sub addActiveNamespace {
    my $self = shift;
    if ( @_ ) {
        $self->{ACTIVE_NAMESPACES} = [uniq( @_, @{$self->{ACTIVE_NAMESPACES}})];
    }
    return $self;
}

sub removeActiveNamespace {
    my $self = shift;
    if ( @_ ) {
        my %remove = map {$_ =>1} @_;
        $self->{ACTIVE_NAMESPACES} = [ grep { !exists $remove{$_}} @{$self->{ACTIVE_NAMESPACES}} ];
    }
    return $self;
}

sub getIncludedFiles{
    return shift->{INCLUDES};
}

sub getNamespaces {
    my $self = shift;
    return uniq(keys %{$self->{ACTIONS}}, GLOBAL_NAMESPACE);
}

sub getNamespaceActions {
    my ($self, $namespace) = @_;
    my @actions; 
    if ( $self->getMeta($namespace, 'auto_detect') ) {
        no strict 'refs';
        my $module = $self->getMeta($namespace, 'module');
        $self->loadModule($module);
        my $globs = \%{$module. '::'};
        for ( keys %$globs ) {
            if ( /^ACTION_/ && $module->can($_) ) {
                push @actions, substr($_, 7);
            }
        }
    }
    if ( exists $self->{ACTIONS}{$namespace} ) {
        push @actions, keys %{$self->{ACTIONS}{$namespace}};
    }
    return uniq(@actions);
}

sub loadModule {
    my ($self, $module) = @_;
    if ( !$module->can('can') ) { # package is not exists
        eval("require $module");
        if ( $@ ) {
            confess "Require $module failed: $@\n";
        }
    }
}

1;
