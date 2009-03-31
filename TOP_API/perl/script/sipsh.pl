#!/home/y/bin/perl -w
# sipsh.pl --- 
# Author: Ye Wenbin <wenbin.ye@alibaba-inc.com>
# Created: 03 Mar 2009
# Version: 0.01

use warnings;
use strict;

use FindBin qw($Bin);
use lib "$Bin/../lib";
use Net::Top2;
use YAML;
use Text::Table;
use Data::Dumper qw(Dumper);
use Getopt::Long;
use Log::Log4perl qw(:easy);
# Log::Log4perl->easy_init($ERROR);
Log::Log4perl->easy_init();
binmode STDOUT, ":utf8";

my ($load_file);
GetOptions(
    'load=s' => \$load_file,
);
my %params;

my $prompt = "top> ";

# my $top = Net::Top2->new;
my $top = Net::Top->new;

## daily
# my $top = Net::Top2->new(
#     top_url => 'http://192.168.208.110/router/rest',
#     top_secret => 'hhhhh',
#     top_appkey => 'yyyy',
# );

my %commands = (
    bind => \&bind_param,
    clean => \&clean_binded,
    show => \&show_binded,
    exit => sub { exit },
);

my %methods = (
    'Net::Top::Request::Item' => [qw/get search update/],
    'Net::Top::Request::User' => [qw/get/],
    'Net::Top::Request::Postage' => [qw/get/],
);
my %api;
for my $class( keys %methods ) {
    for ( @{$methods{$class}} ) {
        (my $subclass = $class) =~ s/.*:://;
        $api{$_.$subclass} = [$class, $_];
    }
}
$api{uploadItemImg} = ['Net::Top::Request::Item', 'imgUpload'];

if ( $load_file ) {
    exec_file($load_file);
}

if ( @ARGV ) {
    exec_file($_) for @ARGV;
} else {
    mainloop();
}

sub exec_file {
    my $file = shift;
    open(my $fh, "<", $file) or die "Can't open file $file: $!";
    while ( <$fh> ) {
        read_eval($_);
    }
}
    
sub mainloop {
    print $prompt;
    while ( <> ) {
        read_eval($_);
        print $prompt;
    }
}

sub read_eval {
    chomp;
    s/^\s+//;
    s/\s+$//;
    return unless $_;
    return if /^#/;
    my ($cmd, @args) = split /\s+/;
    if ( exists $commands{$cmd} ) {
        $commands{$cmd}->(@args);
    } elsif ( exists $api{$cmd} ) {
        call_api($cmd, @args);
    } else {
        print "Unknown commands '$cmd'\n";
    }
}

sub bind_param {
    my ($key, $val) = @_;
    $params{$key} = $val;
}

sub show_binded {
    my $tb = Text::Table->new();
    $tb->load( map { [$_ => $params{$_}] } keys %params );
    print $tb, "\n";
}

sub clean_binded {
    my $key = shift;
    if ( !defined($key) ) {
        %params = ();
    }
    else {
        delete $params{$key};
    }
}

sub call_api {
    my ($name, @args) = @_;
    my $class = $api{$name};
    if ( ref $class ) {
        ($class, $name) = @{$class};
    }
    eval("require $class");
    if ( $@ ) {
        die "'$class' Not implement yet!\n";
    }
    my %args = %params;
    for ( @args ) {
        if ( index($_, '=') < 0 ) {
            print "Wrong param: '$_'\n";
            return;
        }
        my ($key, $val) = split('=', $_, 2);
        $args{$key} = $val;
    }
    if ( exists $args{'fields'} ) {
        $args{'fields'} = [split(',', $args{'fields'})];
    }
    DEBUG(YAML::Dump(\%args));
    my $req = $class->$name(%args);
    DEBUG(YAML::Dump({$req->query_param}));
    my $res = $top->request($req);
    if ( $res->is_success ) {
        print YAML::Dump($res->{_xmlobj}), "\n";
    } else {
        print "Error: ", $res->message, "\n";
    }
}
