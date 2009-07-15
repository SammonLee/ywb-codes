#!/usr/bin/perl -w
# create_meta.pl --- 
# Author: Ye Wenbin <wenbinye@gmail.com>
# Created: 15 Jul 2009
# Version: 0.01

use warnings;
use strict;
use Data::Dumper qw(Dumper);
use JSON::XS;
use Path::Class;
use DBI;
use SQL::Abstract;

my $dbh = DBI->connect(
    'dbi:mysql:dbname=test',
    'root', '',
);

my $stmt = <<SQL;
SELECT cat_name, api_name, param_name, param_type, param_classname, param_value
FROM cat, api, param
WHERE cat.cat_id = api.cat_id
    AND api.api_id = param.api_id
ORDER BY api_name
SQL

my $sth = $dbh->prepare($stmt);
$sth->execute();
my $api_generator = api($sth);
my $json = JSON::XS->new->pretty;
my $savedir = 'meta';

my $i = 0;
while ( my $api = $api_generator->() ) {
    my $first = $api->[0];
    my %data;
    $data{method} = $first->{api_name};
    my @part = split /\./, $data{method};
    shift(@part);
    my $file = file($savedir, join('.', @part).'.json');
    $data{class} = 'Net_Top_Request_' . join('', map { ucfirst($_) } @part);
    $data{api_type} = ucfirst($first->{cat_name});
    my %parameters;
    foreach my $param ( @$api ) {
        if ( $param->{'param_classname'} eq 'isMust' ) {
            push @{$parameters{required}}, $param->{param_name};
        }
        elsif ( $param->{'param_classname'} eq 'mSelect' ) {
            push @{$parameters{optional}}, $param->{param_name};
        } else {
            push @{$parameters{other}}, $param->{param_name};
        }
        if ( $param->{'param_type'} eq 'file' ) {
            push @{$parameters{file}}, $param->{param_name};
            $data{http_method} = 'post';
        }
    }
    $data{parameters} = \%parameters;
    # print Dumper(\%data), "\n";
    my $fh = $file->openw() or die "Can't open $file: $!";
    print {$fh} $json->encode(\%data);
    # exit;
}

sub api {
    my $sth = shift;
    my $last;
    return sub {
        if ( !$last ) {
            $last = $sth->fetchrow_hashref;
        }
        return if !$last;
        my @params = ($last);
        $last = undef;
        while ( my $row = $sth->fetchrow_hashref ) {
            if ( $row->{api_name} eq $params[0]{api_name} ) {
                push @params, $row;
            } else {
                $last = $row;
                last;
            }
        }
        return \@params;
    };
}
