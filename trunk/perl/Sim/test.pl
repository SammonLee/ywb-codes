#!/usr/bin/perl -w
# test.pl --- 

use lib 'lib';
use Sim::User;
use Sim::Client;
use Sim::Server;
use Sim::Session;
require Sim::Config;
use Data::Dumper qw(Dumper);
use POE;
use Log::Log4perl qw(:easy);
use Readonly;

my $log4perl_conf = << 'CONF';
log4perl.logger.Sim.Server = INFO, FileApp
log4perl.appender.FileApp = Log::Dispatch::FileRotate
log4perl.appender.FileApp.mode = append
log4perl.appender.FileApp.filename = test.log
log4perl.appender.FileApp.max = 24
log4perl.appender.FileApp.DatePattern = 0:0:0:0:0:5:0
log4perl.appender.FileApp.TZ          = PST
log4perl.appender.FileApp.layout = Log::Log4perl::Layout::PatternLayout
log4perl.appender.FileApp.layout.ConversionPattern = %m%n
CONF

Log::Log4perl->init(\$log4perl_conf);

Readonly my $MAX_USER => 10;
Readonly my $MAX_SESSION => 1;
my $user_counts = 0;

sub handler_user_start {
    my ($kernel, $heap, $session) = @_[KERNEL, HEAP, SESSION];
    my $shops = $Sim::Config->{shops};
    my $user = new Sim::User();
    my $client = new Sim::Client();
    my $server = new Sim::Server();
    # my $time = time();
    # $server->set('time', $time);
    $server->auto_time(1);
    $client->set('server', $server);

    my $ses = new Sim::Session();
    $ses->set('shop', $shops->[rand(@$shops)]);
    $ses->add_rand_pages(rand(10)+5);

    $user->set('client', $client);
    $user->set('session', $ses);
    $heap->{user} = $user;
    DEBUG("user created");
    $user_counts++;
    $kernel->yield('browsing');
}

sub handler_browsing {
    my ($kernel, $heap, $session) = @_[KERNEL, HEAP, SESSION];
    my $user = $heap->{user};
    my $page = $user->session->next;
    if ( defined $page ) {
        $user->client->request($page->[0]);
        $kernel->delay( 'browsing', $page->[1] );
    }
}

sub handler_user_end {
    DEBUG("user destoryed");
    $user_counts--;
}

sub handler_start {
    my ($kernel, $heap, $session) = @_[KERNEL, HEAP, SESSION];
    $kernel->yield('create_user');
}

sub handler_create_user {
    my ($kernel, $heap, $session) = @_[KERNEL, HEAP, SESSION];
    DEBUG("user count: $user_counts");
    if ( $user_counts < $MAX_USER ) {
        POE::Session->create(
            'inline_states' => {
                _start => \&handler_user_start,
                'browsing' => \&handler_browsing,
                '_stop' => \&handler_user_end,
            }
        );
    }
    $kernel->delay('create_user', rand(3));
}

for ( 1..$MAX_SESSION ) {
    POE::Session->create(
        'inline_states' => {
            '_start' => \&handler_start,
            'create_user' => \&handler_create_user,
        }
    );
}

POE::Kernel->run();
exit;

