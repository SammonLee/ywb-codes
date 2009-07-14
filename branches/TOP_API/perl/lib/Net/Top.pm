package Net::Top;

use strict; 
use warnings;

use Carp;
use LWP::UserAgent;
use base 'Class::Accessor';
use URI;
use Digest::MD5 qw(md5_hex);
use Log::Log4perl qw(:easy);
use Data::Dumper qw(Dumper);
use Readonly;
use POSIX qw/strftime/;

__PACKAGE__->mk_accessors(qw/top_url top_appkey top_secret_key top_login_url/);

Readonly our $TOP_URL => 'http://sip.alisoft.com/sip/rest';
Readonly our $TOP_LOGIN_URL => 'http://sip.alisoft.com/sip/login';

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = $class->SUPER::new(@_);
    bless $self, $class;
    if ( !$self->top_url ) {
        $self->top_url($TOP_URL);
    }
    if ( !$self->top_login_url ) {
        $self->top_login_url($TOP_LOGIN_URL);
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
        DEBUG({
            filter => \&Data::Dumper::Dumper,
            value => {
                url => "$u",
                query => $query,
            }});
        $res = $self->{ua}->post("$u", $query, @args);
    } else {
        croak "Use post method if want to upload file!\n" if $form_data;
        $u->query_form( $query );
        DEBUG("GET $u");
        $res = $self->{ua}->get($u);
    }
    return $req->_response($res);
}

sub query_param {
    my ($self, $req) = @_;
    my %query = $req->query_param;
    $query{sip_apiname} = $req->_api_method;
    $query{sip_appkey} = $self->top_appkey;
    # $query{sip_timestamp} = strftime('%Y-%m-%d %H:%M:%S.000', localtime);
    $query{sip_timestamp} = '2009-04-08 23:50:00.000';
    if ( exists $query{session} ) {
        $query{sip_sessionid} = delete $query{session};
    }
    $query{v} = '1.0';
    my $str = $self->top_secret_key . join('', map { $_.$query{$_} } sort grep {!ref $query{$_}} keys %query);
    $query{sip_sign} = uc(md5_hex( $str ));
    return \%query;
}

# http://sip.alisoft.com/sip/login?sip_apiname=taobao.items.onsale.get&sip_appkey=10544&sip_sessionid=wenbinye&sip_apptype=1&sip_applevel=level1&sip_sign=A556AA37DF210F6C786B0AF2DEDCD12A&sip_redirecturl=http%3A%2F%2Ftaobaoassistant.appspot.com%2Fitem%2Fonsale
sub login_url {
    my $self = shift;
    my $args = shift;
    my $params = {
        sip_appkey => $self->top_appkey,
        sip_apiname => $args->{apiname},
        sip_sessionid => $args->{session},
        sip_apptype => 1,
        sip_applevel => 'level1',
        sip_redirecturl => $args->{redirect},
    };
    my $u = URI->new($self->top_login_url);
    $u->query_form($params);
    return $u;
}

1;
