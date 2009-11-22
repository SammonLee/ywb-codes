package Farsail::EventDispatcher;

use strict; 
use warnings;

use Carp;

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = {};
    bless $self, $class;
    return $self;
}

sub connect {
    my ($self, $name, $listener) = @_;
    if ( ref $listener eq 'CODE' ) {
        push @{$self->{$name}}, $listener;
        return 1;
    }
}

sub disconnect {
    my ($self, $name, $listener) = @_;
    my $listeners = $self->{$name};
    if ( ref $listeners ) {
        for ( 0..$#$listeners ) {
            if ( $listeners->[$_] == $listeners ) {
                delete $listeners->[$_];
                return 1;
            }
        }
    }
}

sub notify {
    my ($self, $event) = @_;
    for ( $self->getEventListeners($event->getName()) ) {
        $_->($event);
    }
}

sub notifyUntil{
    my ($self, $event) = @_;
    for ( $self->getEventListeners($event->getName()) ) {
        if ( $_->($event) ) {
            return 1;
        }
    }
}

sub filter {
    my ($self, $event, $value) = @_;
    for ( $self->getEventListeners($event->getName()) ) {
        $value = $_->($event, $value);
    }
    $event->setReturnValue($value);
    return $value;
}

sub getEventListeners{
    my ($self, $name) = @_;
    if ( ref $self->{$name} ) {
        return grep { $_ } @{$self->{$name}};
    } else {
        return ();
    }
}

1;
