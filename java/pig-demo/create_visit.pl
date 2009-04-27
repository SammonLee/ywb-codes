#!/home/y/bin/perl -w
# create_visit.pl ---

my $url = 'http://localhost';
my $file = "id.out";
open(my $fh, "<", $file) or die "Can't open file $file: $!";
chomp(my @users = <$fh>);

for ( 1..10 ) {
    print join("\t", $users[rand(@users)], $url, time()-int(rand(1000))), "\n";
}
