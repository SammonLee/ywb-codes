package Net::TopSip;

use strict; 
use warnings;

use Carp;
use LWP::UserAgent;
use base 'Class::Accessor';
use URI;
use Digest::MD5 qw(md5_hex);
use Log::Log4perl qw(:easy);
use Data::Dumper qw(Dumper);
use POSIX qw/strftime/;

__PACKAGE__->mk_accessors(qw/
top_url top_appkey top_secret
                            /);

my $TOP_URL = 'http://sip.alisoft.com/sip/rest';

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = $class->SUPER::new(@_);
    bless $self, $class;
    if ( !$self->top_url ) {
        $self->top_url($TOP_URL);
    }
    $self->{ua} = LWP::UserAgent->new();
    return $self;
}

sub request {
    my ($self, $req) = @_;
    unless ( my $ret = $req->check() ) {
        croak "Bad request: $ret!\n";
    }
    my $u = URI->new($self->top_url);
    my $query = $self->query_param($req);
    my $form_data = grep { ref $query->{$_} } keys %$query;
    my $res;
    if ( $req->_http_method eq 'post' ) {
        my @args;
        if ($form_data) {
            push @args, 'Content_Type' => 'form-data';
        }
        DEBUG(Dumper([ "$u", $query, @args]));
        $res = $self->{ua}->post("$u", $query, @args);
    } else {
        croak "Use post method if want to upload file!\n" if $form_data;
        $u->query_form( $query );
        DEBUG($u);
        $res = $self->{ua}->get($u);
    }
    return $req->_response($res);
}

sub query_param {
    my ($self, $req) = @_;
    my %query = $req->query_param;
    $query{sip_apiname} = $req->_api_method;
    $query{sip_appkey} = $self->top_appkey;
    $query{sip_timestamp} = strftime('%Y-%m-%d %H:%M:%S.000', localtime);
    if ( exists $query{session} ) {
        $query{sip_sessionid} = delete $query{session};
    }
    $query{v} = '1.0';
    my $str = $self->top_secret . join('', map { $_.$query{$_} } sort grep {!ref $query{$_}} keys %query);
    DEBUG($str);
    $query{sip_sign} = uc(md5_hex( $str ));
    DEBUG(Dumper(\%query));
    return \%query;
}

1;
