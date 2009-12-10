package Farsail::Config;

use strict; 
use warnings;

use Carp;
use base 'AppConfig', 'Clone';
use Farsail::Util qw/expand_file find_file/;
use Log::Log4perl qw/:easy/;

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = $class->SUPER::new( {
        CREATE => 1,
        CASE => 1,
        GLOBAL => {
            ARGCOUNT => AppConfig::ARGCOUNT_ONE,
        },
    });
    $self->define('include=s@');
    bless $self, $class;
    $self->{STATE}{INCLUDES} = {};  # store included files
    if ( @_ ) {
        my $conf = shift;
        if ( ref $conf eq 'HASH' ) {
            $self->setConfig($conf);
        } elsif ( $conf && -e $conf ) {
            $self->setConfigFile($conf);
        }
    }
    return $self;
}

sub setConfig {
    my ($self, $conf) = @_;
    for ( keys %$conf ) {
        $self->set($_, $conf->{$_});
    }
}

sub setConfigFile {
    my ($self, $file) = @_;
    $file = expand_file($file);
    $self->{CONFIG_FILE} = $file;
    $self->file($file);
    return $self;
}

sub getConfigDir {
    return shift->{CONFIG_FILE}->dir;
}

sub getConfigFile {
    return shift->{CONFIG_FILE};
}

sub getExpandFile {
    my $self = shift;
    my $file = $self->get(@_);
    if ( $file ) {
        return expand_file($file, $self->getConfigDir());
    }
}

sub get {
    my ($self, $name, $def) = @_;
    if ( $self->_exists($name) ) {
        return $self->SUPER::get($name);
    }
    return $def;
}

sub getSection {
    my ($self, $section_name, $is_array) = @_;
    my %vars = $self->varlist('^'.quotemeta($section_name.'.'), 1);
    if ( $is_array ) {
        my @vars;
        foreach ( keys %vars ) {
            my ($idx, $key) = split /\./, $_, 2;
            if ( $key ) {
                $vars[$idx]{$key} = $vars{$_};
            } else {
                $vars[$idx] = $vars{$_};
            }
        }
        return \@vars;
    }
    return \%vars;
}

sub file {
    my ($self, $file) = @_;
    my $state = $self->{STATE};
    if ( exists $self->{STATE}{INCLUDES}{$file} ) {
        return;
    }
    $self->{STATE}{INCLUDES}{$file}++;
    DEBUG("Load config '$file'");
    $self->SUPER::file( "$file" );
    if ( $self->_exists("include") ) {
        my $include = $self->get('include');
        $state->{VARIABLE}{include} = [];
        foreach ( @$include ) {
            my @files = find_file(expand_file($_, $self->getConfigFile()->dir()));
            if ( @files ) {
                foreach ( @files ) {
                    $self->file($_);
                }
            } else {
                ERROR("Can't find config file '$_'");
            }
        }
    }
}

sub getIncludedFiles {
    return shift->{STATE}{INCLUDES};
}

1;

__END__

=head1 NAME

Farsail::Config - Farsail module for reading configuration files

=head1 SYNOPSIS



=head1 DESCRIPTION


=head1 METHODS

=over 4

=item new( options )

options can be a hash ref or a file name.

=back

=cut

