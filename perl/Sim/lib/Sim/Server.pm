package Sim::Server;

use strict; 
use warnings;
use HTTP::Response;
use URI;
use URI::Escape;
use MIME::Base32 qw/RFC/;
use Digest::MD5 qw/md5_hex/;
use Carp;
use POSIX qw/strftime/;
use base qw(Class::Accessor);
use Readonly;
use Log::Log4perl qw/:easy/;

__PACKAGE__->mk_accessors(qw/time cookie request auto_time/);

Readonly my $COOKIE_NAME => 'B';
Readonly my $SECRET_KEY => '49ff105cca19b';

sub time {
    my $self = shift;
    if ( $self->auto_time ) {
        return CORE::time();
    } else {
        if ( @_ ) {
            $self->{time} = shift;
        }
        return $self->{time};
    }
}

sub response {
    my ($self, $req) = @_;
    my $res = new HTTP::Response();
    my $cookie = $req->header('Cookie');
    if ( !$self->check_cookie($cookie) ) {
        $res->header('set-cookie', $self->gen_cookie());
    }
    $self->request($req);
    $res->content( $self->gen_content($req->uri) );
    $self->write_log();
    return $res;
}

sub write_log {
    my $self = shift;
    my $beacon = $self->request->uri;
    my $url = $self->request->header('Referer');
    my $shop = Sim::Config->get_shop_by_beacon($beacon);
    my %log = (
        'B' => $self->cookie->{'b'},
        'i' => '',
        't' => strftime('%Y-%m-%d %T', localtime($self->time)),
        # 'u' => $beacon,
        'r' => $url,
        'f' => '',
        's' => $shop->{id},
        'e' => extract_item_id($url),
        'a' => '',
        'p' => '',
    );
    DEBUG({filter => \&Data::Dumper::Dumper, value => \%log});
    INFO("\x01".join("\x01", map { $_.$log{$_} }
                    grep { defined($log{$_}) && $log{$_} ne '' }
                        keys %log));
}

sub extract_item_id {
    my $u = URI->new(shift);
    my %query = $u->query_form;
    return $query{'itemid'};
}

sub gen_content {
    return 1;
}

sub gen_cookie {
    my $self = shift;
    my $prefix = $COOKIE_NAME. '=';
    my $bcookie = pack('I', int(rand(~0))) . pack('I', CORE::time());
    my %cookies = (
        'b' => MIME::Base32::encode($bcookie),
        's' => substr(md5_hex($bcookie.$SECRET_KEY), -2),
        'v' => 1
    );
    my $u = URI->new;
    $u->query_form(%cookies);
    $self->cookie(\%cookies);
    return $prefix . $u->query;
}

sub pass {
    my ($self, $interval) = @_;
    if ( $interval ) {
        $self->set('time', $self->time + int($interval));
    }
}

sub check_cookie {
    my ($self, $cookie) = @_;
    my $prefix = $COOKIE_NAME. '=';
    return unless $cookie && $cookie =~ /^$prefix/;
    $cookie = uri_unescape(substr($cookie, length($prefix)));
    my $u = URI->new;
    $u->path_query('?'.$cookie);
    my %cookies = $u->query_form;
    $self->cookie(\%cookies);
    return unless $cookies{b} && $cookies{s};
    return $cookies{s} eq substr( md5_hex(MIME::Base32::decode($cookies{b}).$SECRET_KEY), -2 );
}

1;
__END__

=head1 NAME

Sim::Server - Perl extension for blah blah blah

=head1 SYNOPSIS

   use Sim::Server;
   blah blah blah

=head1 DESCRIPTION

Stub documentation for Sim::Server, 

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

Ye Wenbin, E<lt>wenbinye@gmail.comE<gt>

=head1 COPYRIGHT AND LICENSE

Copyright (C) 2009 by Ye Wenbin

This program is free software; you can redistribute it and/or modify
it under the same terms as Perl itself, either Perl version 5.8.2 or,
at your option, any later version of Perl 5 you may have available.

=head1 BUGS

None reported... yet.

=cut
