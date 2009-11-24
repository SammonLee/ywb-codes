package Farsail::Args;

use strict; 
use warnings;

use Carp;

use AppConfig qw(:argcount);
use base 'AppConfig', 'Clone';
use Return::Value;

our %ARGS_TYPE = (
    'string' => { ARGCOUNT => ARGCOUNT_ONE },
    'bool' => { ARGCOUNT => ARGCOUNT_NONE },
    'date' => { ARGCOUNT => ARGCOUNT_ONE,
                VALIDATE => sub { return check_date($_[1]) },
                },
    'int' => { ARGCOUNT => ARGCOUNT_ONE,
               VALIDATE => '^\d+$' },
    'array' => { ARGCOUNT => ARGCOUNT_LIST },
    'hash' => { ARGCOUNT => ARGCOUNT_HASH },
);

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = $class->SUPER::new({
        ERROR => sub {
            my $format = shift;
            confess sprintf("$format\n", @_);
        }
    });
    $self->{STATE}{REQUIRES} = {};  # required argments
    bless $self, $class;
    return $self;
}

sub define {
    my $self = shift;
    my $state = $self->{STATE};
    my $requires = $state->{REQUIRES};
    while ( @_ ) {
        my $var = shift;
        my $cfg = ref($_[0]) eq 'HASH' ? shift : {};
        if ( exists $cfg->{type} ) { # predefine type
            my $opt = $ARGS_TYPE{$cfg->{type}};
            if ( $cfg->{default} ) {
                $opt->{DEFAULT} = $cfg->{default};
            }
            elsif ( $cfg->{require} && $opt->{ARGCOUNT} != ARGCOUNT_NONE ) {
                $requires->{$var} = 1;
            }
            $cfg = $opt;
        }
        $self->SUPER::define($var, $cfg);
    }
}

sub get {
    my ($self, $name, $def) = @_;
    if ( $self->_exists($name) ) {
        my $res =  $self->SUPER::get($name);
        if ( defined $res ) {
            return $res;
        }
    }
    return $def;
}

sub checkRequires {
    my $self = shift;
    my $state = $self->{STATE};
    my @missing;
    for ( keys %{$state->{REQUIRES}} ) {
        if ( !defined $self->get($_) ) {
            push @missing, $_;
        }
    }
    if ( @missing ) {
        return new Return::Value(
            'type' => 'failure',
            'data' => \@missing
        );
    }
    return 1;
}

1;
