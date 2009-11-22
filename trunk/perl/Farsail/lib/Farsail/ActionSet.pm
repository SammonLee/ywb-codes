package Farsail::ActionSet;

use strict; 
use warnings;
use Carp;

use YAML qw/LoadFile/;
use Farsail::Action;
use Farsail::Util qw/expand_file find_file/;
use Scalar::Util qw/blessed/;
use constant GLOBAL_NAMESPACE => 'global';
use Log::Log4perl qw/:easy/;

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = {};
    bless $self, $class;
    $self->{ACTIONS} = {};
    $self->{INCLUDES} = {};
    $self->{NAMESPACES} = [ GLOBAL_NAMESPACE ];
    $self->{MODULES} = {};
    $self->{TYPES} = {};
    if ( @_ ) {
        my $actions = shift;
        if ( ref $actions eq 'HASH' ) {
            $self->setActions($actions);
        } else {
            $self->setActionsFile($actions);
        }
    }
    return $self;
}

sub setActions {
    my ($self, $defs) = @_;
    my $include = delete $defs->{include};
    my $actions = $self->{ACTIONS};
    foreach my $ns ( keys %$defs ) {
        if ( ref $defs->{$ns} eq 'HASH' ) {
            if ( exists $defs->{$ns}{module} ) {
                $self->{MODULES}{$ns} = delete $defs->{$ns}{module};
            }
            if ( exists $defs->{$ns}{types} ) {
                my $types = delete $defs->{$ns}{types};
                foreach ( keys %$types ) {
                    $self->{TYPES}{$ns}{$_} = $types->{$_};
                }
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

sub getActionsFile {
    return shift->{ACTIONS_FILE};
}

sub setActionsFile {
    my ($self, $file) = @_;
    $file = expand_file($file);
    $self->{ACTIONS_FILE} = $file;
    $self->loadFile($file);
    return $file;
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
    $self->setActions($actions);
}

sub hasAction {
    my ($self, $action, $namespace) = @_;
    my (@ns, $action_name);
    if ( blessed($action) && $action->isa('Farsail::Action') ) {
        push @ns, $action->getNamespace();
        $action_name = $action->getName();
    } else {
        if ( index('.', $action) ) {
            my @r = split /\./, $action, 2;
            push @ns, $r[0];
            $action_name = $r[1];
        } else {
            @ns = $namespace ? @$namespace : $self->getNamespaces();
            $action_name = $action;
        }
    }
    for ( @ns ) {
        if ( exists $self->{ACTIONS}{$_}{$action_name} ) {
            return ($_, $action_name);
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
            return $self->createAction($ns, $name);
        }
    } else {
        ERROR("Unknown action '$action'");
    }
}

sub createAction {
    my ($self, $ns, $name) = @_;
    my $action = $self->{ACTIONS}{$ns}{$name};
    if ( !$action ) {
        die("Unknown action '$ns.$name'");
    }
    if ( blessed($action) ) {
        return $action;
    }
    return $self->{ACTIONS}{$ns}{$name} = new Farsail::Action(
        actions => $self,
        namespace => $ns,
        name => $name,
        action => $action
    );
}

sub getNamespaces {
    return @{shift->{NAMESPACES}};
}

sub addNamespace {
    my $self = shift;
    $self->{NAMESPACES} = [uniq( @_, @{$self->{NAMESPACES}})];
    return $self;
}

sub removeNamespace {
    my $self = shift;
    my %remove = map {$_ =>1} @_;
    $self->{NAMESPACES} = [ grep { !exists $remove{$_}} @{$self->{NAMESPACES}} ];
    return $self;
}

sub getModule{
    my ($self, $ns) = @_;
    return $self->{MODULES}{$ns};
}

sub getTypes {
    my ($self, $ns) = @_;
    return $self->{TYPES}{$ns};
}

sub getIncludedFiles{
    return shift->{INCLUDES};
}

1;
