package Farsail::Action;

use strict; 
use warnings;
use Log::Log4perl qw(:easy);
use Carp;

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = {};
    bless $self, $class;
    my %opts = @_;
    for ( qw/name namespace actions/ ) {
        if ( !exists $opts{$_} ) {
            die("Can't create action without '$_'");
        }
        $self->{$_} = $opts{$_};
    }
    my $action = $opts{action};
    $self->{module} = $action->{module}
        || $self->{actions}->getMeta($self->{namespace}, 'module');
    $self->setDepends($action->{depends});
    $self->setArgs($action->{args});
    return $self;
}

sub setNamespace {
    my ($self, $namespace) = @_;
    $self->{namespace} = $namespace;
    return $self;
}

sub getNamespace {
    return shift->{namespace};
}

sub setName {
    my ($self, $name) = @_;
    $self->{name} = $name;
    return $self;
}

sub getName {
    return shift->{name};
}

sub getFullName {
    my $self = shift;
    return $self->{namespace}.'.'.$self->{name};
}

sub setModule {
    my ($self, $module) = @_;
    $self->{module} = $module;
    return $self;
}

sub getModule {
    return shift->{module};
}

sub setActions {
    my ($self, $actions) = @_;
    $self->{actions} = $actions;
    return $self;
}

sub getActions {
    return shift->{actions};
}

sub setArgs {
    my ($self, $args) = @_;
    if ( $args && ref $args eq 'HASH' ) {
        my $types = $self->{actions}->getMeta($self->{namespace}, 'types') || {};
        foreach ( keys %$args ) {
            my $opt = $args->{$_};
            if ( !ref $opt ) {
                $opt = { type => $opt };
            }
            if ( exists $types->{$opt->{type}} ) {
                my $def = $types->{$opt->{type}};
                delete $opt->{type};
                for ( keys %$def ) {
                    $opt->{$_} = $def->{$_} if !exists $opt->{$_};
                }
            }
            $args->{$_} = $opt;
        }
    } else {
        $args = {};
    }
    $self->{args} = $args;
    return $self;
}

sub getArgs {
    return shift->{args};
}

sub setDepends {
    my ($self, $depends) = @_;
    my @deps;
    if ( ref $depends ) {
        my @namespaces = ($self->{namespace}, $self->{actions}->getActiveNamespaces());
        foreach ( @$depends ) {
            my $dep = $self->{actions}->getAction($_, \@namespaces);
            if ( $dep ) {
                push @deps, $dep;
            }
        }
    }
    $self->{depends} = \@deps;
    return $self;
}

sub getDepends {
    return @{shift->{depends}};
}

sub getAllArgs {
    my $self = shift;
    my %args = %{$self->getArgs()};
    for ( @{$self->getAllDepends()} ) {
        my $sub_args = $_->getArgs();
        for ( keys %$sub_args ) {
            $args{$_} = $sub_args->{$_} if !exists $args{$_};
        }
    }
    return \%args;
}

sub getAllDepends {
    my ($self, $seen) = @_;
    $seen ||= {};
    my @deps;
    for ( $self->getDepends() ) {
        if ( !exists $seen->{$_->getFullName()}) {
            $seen->{$_->getFullName()}++;
            push @deps, $_;
            push @deps, @{$_->getAllDepends($seen)};
        }
    }
    return \@deps;
}

sub call {
    my ($self, $farsail) = @_;
    my $method = 'ACTION_' . $self->getName();
    my $class = $self->getModule();
    if ( $class->can($method) ) {
        $class->$method($farsail);
    } else {
        ERROR( "$class doesn't implement '$method'");
    }
}

1;
