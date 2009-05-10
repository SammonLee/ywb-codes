use FindBin qw/$Bin/;
use lib "$Bin/lib";
use Data::Dumper qw(Dumper);
use Sim::Config;
use Sim::User;
use Sim::Client;
use Sim::Server;
use Sim::Session;
use Data::Dumper qw(Dumper);
use Log::Log4perl qw(:easy);
use Readonly;
Log::Log4perl->easy_init();

my $shops = $Sim::Config->{shops};
my @shop_ids = keys %$shops;
my $user = new Sim::User();
my $client = new Sim::Client();
my $server = new Sim::Server();
# my $time = time();
# $server->set('time', $time);
$server->auto_time(1);
$client->set('server', $server);

my $ses = new Sim::Session();
$ses->set('shop', $shops->{@shop_ids[rand(@shop_ids)]});
$ses->add_rand_pages(rand(10)+5);

$user->set('client', $client);
$user->set('session', $ses);

my $page = $user->session->next;
print Dumper($page), "\n";
$user->client->request($page->[0]);
