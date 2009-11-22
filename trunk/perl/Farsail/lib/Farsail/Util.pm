package Farsail::Util;

use strict; 
use warnings;

use Carp;
use Path::Class;
use File::Glob qw/:glob/;

require Exporter;
our @ISA = qw(Exporter);
our %EXPORT_TAGS = ( 'all' => [ qw( check_date parse_date expand_file find_file ) ] );
our @EXPORT_OK = ( @{ $EXPORT_TAGS{'all'} } );
our @EXPORT = qw(  );

sub check_date {
    my $dt = eval{ parse_date(@_) };
    return ( !$@ && $dt );
}

sub parse_date {
    return if !$_[0];
    if ( my ($year, $month, $day) =
             ($_[0] =~ /(\d{4})[-\/]?(\d{2})[-\/]?(\d{2})/) ) {
        require DateTime;
        return DateTime->new(
            year=>$year, month=>$month, day=>$day,
        );
    } else {
        require DateTime::Format::Natural;
        my $parser = DateTime::Format::Natural->new(
            time_zone=>'local'
        );
        my $dt = $parser->parse_datetime($_[0]);
        return ( $parser->success ? $dt : undef);
    }
}

sub expand_file {
    my $file = file(shift);
    if ( $file->is_absolute ) {
        return $file;
    }
    if ( @_ ) {
        return file(shift, $file)->absolute;
    } else {
        return file($file)->absolute;
    }
}

sub find_file {
    my $file = shift;
    if ( -e $file ) {
        return ($file);
    }
    return bsd_glob($file, GLOB_ERROR);
}

1;

__END__

=head1 NAME

Farsail::Util - Utility functions

=head1 SYNOPSIS



=head1 DESCRIPTION


=head1 METHODS

=over

=item Path::Class::File expand_file($file[, $dir])

Return absolute file name for $file. 

=back

=cut

