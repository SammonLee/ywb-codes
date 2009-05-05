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
use Readonly;

Readonly my $MAX_USER => 10;

sub handler_start {
    my ($kernel, $heap, $session) = @_[KERNEL, HEAP, SESSION];
    my $shops = $Sim::Config->{shops};
    my $user = new Sim::User();
    my $client = new Sim::Client();
    my $server = new Sim::Server();
    my $time = time();
    $server->set('time', $time);
    $client->set('server', $server);

    my $ses = new Sim::Session();
    $ses->set('shop', $shops->[rand(@$shops)]);
    $ses->add_rand_pages(rand(10)+5);

    $user->set('client', $client);
    $user->set('session', $ses);
    $heap->{user} = $user;
    $kernel->yield('browsing');
}

sub handler_browsing {
    my ($kernel, $heap, $session) = @_[KERNEL, HEAP, SESSION];
    $kernel->yield('browsing') if $heap->{user}->browsing();
}

for ( 1..$MAX_USER ) {
    POE::Session->create(
        'inline_states' => {
            _start => \&handler_start,
            'browsing' => \&handler_browsing,
            }
    );
}

POE::Kernel->run();
exit;

