#!/usr/bin/perl -w
# load_data.pl --- 
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

my $sql = SQL::Abstract->new;
load_cat('cat_list.json');
load_api('api_list.json');
load_param('api_params.json');

sub load_cat {
    my $file= shift;
    my $data = decode_json(file($file)->slurp());
    my $sth;
    foreach my $row ( @$data ) {
        my ($stmt, @binds) = $sql->insert('cat', $row);
        $sth ||= $dbh->prepare($stmt);
        $sth->execute(@binds);
    }
}

sub load_api {
    my $file = shift;
    my $data = decode_json(file($file)->slurp());
    my $sth ;
    foreach my $cat_id ( keys %$data ) {
        foreach my $api_id ( keys %{$data->{$cat_id}} ) {
            my %row = (
                api_id => $api_id,
                cat_id => $cat_id,
                api_name => $data->{$cat_id}{$api_id}
            );
            my ($stmt, @binds) = $sql->insert('api', \%row);
            $sth ||= $dbh->prepare($stmt);
            $sth->execute(@binds);
        }
    }
}

sub load_param {
    my $file = shift;
    my $data = decode_json(file($file)->slurp());
    my $sth ;
    foreach my $api_id ( keys %$data ) {
        foreach my $param ( @{$data->{$api_id}} ) {
            my %row = (
                'api_id' => $api_id,
            );
            foreach ( keys %$param ) {
                $row{'param_' . $_} = $param->{$_};
            }
            my ($stmt, @binds) = $sql->insert('param', \%row);
            $sth ||= $dbh->prepare($stmt);
            $sth->execute(@binds);
        }
    }
}
