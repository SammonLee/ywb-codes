use FindBin qw/$Bin/;
use lib "$Bin/lib";
use Data::Dumper qw(Dumper);
use Sim::Config;

print Dumper( \%Sim::Config::), "\n";
