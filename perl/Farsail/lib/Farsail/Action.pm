package Farsail::Action;

use strict; 
use warnings;

use Carp;

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = {};
    bless $self, $class;
    my %opts = @_;
    for ( qw/name module actions/ ) {
        if ( !$opts{$_} ) {
            die("Can't create action without '$_'");
        }
        $self->{$_} = $opts{$_};
    }
    
    return $self;
}

sub setName {
    my ($self, $name) = @_;
    $self->{name} = $name;
    return $self;
}

sub getName {
    return shift->{name};
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

sub getArgs {
}

sub getDepends {
}

sub call {
}

1;
