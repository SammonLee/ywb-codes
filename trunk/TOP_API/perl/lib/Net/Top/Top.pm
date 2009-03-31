package Net::Top;

use strict; 
use warnings;

use Carp;

use base 'Net::TopSip';
use Digest::MD5 qw(md5_hex);
use POSIX qw/strftime/;
my $TOP_URL = '';
my $TOP_APPKEY = '';
my $TOP_SECRET = '';

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $opt = {
        top_url => $TOP_URL,
        top_appkey => $TOP_APPKEY,
        top_secret => $TOP_SECRET,
        @_
    };
    my $self = $class->SUPER::new($opt);
    bless $self, $class;
    return $self;
}

sub query_param {
    my ($self, $req) = @_;
    my %query = $req->query_param;
    $query{method} = $req->_api_method;
    $query{api_key} = $self->top_appkey;
    $query{timestamp} = strftime('%Y-%m-%d %H:%M:%S.000', localtime);
    $query{v} = '1.0';
    my $query_string = join('', map { $_.$query{$_} } sort grep {!ref $query{$_}} keys %query);
    $query{sign} = uc(md5_hex( $self->top_secret . $query_string ));
    return \%query;
}

1;
__END__

=head1 NAME

Net::Top2 - Perl extension for blah blah blah

=head1 SYNOPSIS

   use Net::Top2;
   blah blah blah

=head1 DESCRIPTION

Stub documentation for Net::Top2, 

Blah blah blah.

=head2 EXPORT

None by default.

=head1 SEE ALSO

Mention other useful documentation such as the documentation of
related modules or operating system documentation (such as man pages
in UNIX), or any relevant external documentation such as RFCs or
standards.

If you have a mailing list set up for your module, mention it here.

If you have a web site set up for your module, mention it here.

=head1 AUTHOR

Ye Wenbin, E<lt>wenbin.ye@alibaba-inc.comE<gt>

=head1 COPYRIGHT AND LICENSE

Copyright (C) 2009 by Ye Wenbin

This program is free software; you can redistribute it and/or modify
it under the same terms as Perl itself, either Perl version 5.8.2 or,
at your option, any later version of Perl 5 you may have available.

=head1 BUGS

None reported... yet.

=cut
