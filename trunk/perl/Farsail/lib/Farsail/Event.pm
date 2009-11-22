package Farsail::Event;

use strict; 
use warnings;

use Carp;

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my %opts = @_;
    if ( !$opts{name} ) {
        die("Event name is required");
    }
    my $self = {};
    bless $self, $class;
    $self->setName($opts{name})
        ->setSubject($opts{subject});
    return $self;
}

sub setName {
    my $self = shift;
    $self->{name} = shift;
    return $self;
}

sub getName {
    return shift->{name};
}

sub setSubject {
    my $self = shift;
    $self->{subject} = shift;
    return $self;
}

sub getSubject {
    return shift->{subject};
}

sub setReturnValue {
    my $self = shift;
    $self->{return_value} = shift;
    return $self;
}

sub getReturnValue {
    return shift->{return_value};
}

1;
