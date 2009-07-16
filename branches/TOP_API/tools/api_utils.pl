#!/usr/bin/perl -w
# api_utils.pl --- 
# Author: Ye Wenbin <wenbinye@gmail.com>
# Created: 15 Jul 2009
# Version: 0.01

use warnings;
use strict;
use Data::Dumper qw(Dumper);
use List::Util qw(max);
use JSON::XS;
use Path::Class;
use DBI;
use SQL::Abstract;

my $dbh = DBI->connect(
    'dbi:mysql:dbname=test',
    'root', '',
);

my $sql = SQL::Abstract->new;
# init();
export_all();

sub export_all {
    my $stmt = $sql->select('api', 'api_name');
    my $apis = $dbh->selectall_arrayref($stmt);
    my $coder = JSON::XS->new->pretty;
    foreach my $api ( @$apis ) {
        my $fh = file('meta', $api->[0].'.json' )->openw;
        my $api = get_api( $api->[0] );
        print {$fh} $coder->encode($api);
    }
}

sub get_api {
    my $api_name = shift;
    my ($stmt, @binds, $sth);
    # get api general info
    ($stmt, @binds) = $sql->select(
        ['api', 'cat'],                                     # tables
        ['cat_name', 'api_id', 'api_name', 'is_secure', 'list_tags'], # fields
        {'api_name' => $api_name,
         'api.cat_id' => \'=cat.cat_id' } # where
    );
    $sth = $dbh->prepare($stmt);
    $sth->execute(@binds);
    my $api = $sth->fetchrow_hashref;
    
    # get Parameters
    ($stmt, @binds) = $sql->select(
        'param',
        ['param_name', 'param_type', 'param_classname', 'param_value', 'param_desc'],
        { 'api_id' => $api->{api_id} }
    );
    $sth = $dbh->prepare($stmt);
    $sth->execute(@binds);
    while ( my $row = $sth->fetchrow_hashref ) {
        my %param;
        while ( my ($key, $val) = each(%$row) ) {
            (my $short = $key) =~ s/^param_//;
            $param{$short} = $val;
        }
        push @{$api->{parameters}}, \%param;
    }
    # get api fields
    ($stmt, @binds) = $sql->select(
        'fields',
        ['fields_name', 'fields_value'],
        { 'api_id' => $api->{api_id} }
    );
    $sth = $dbh->prepare($stmt);
    $sth->execute(@binds);
    while ( my $row = $sth->fetchrow_hashref ) {
        $api->{fields}{$row->{fields_name}}
            = [split(/\s*,\s*/, $row->{fields_value})];
    }
    # normalize to output data structure
    my %data;
    $data{method} = $api->{api_name};
    my @parts = split '\.', $data{method};
    shift(@parts);
    $data{class} = 'Net_Top_Request_' . join('', map { ucfirst } @parts);
    $data{api_type} = ucfirst($api->{cat_name});
    if ( $api->{is_secure} ) {
        $data{'is_secure'} = $api->{is_secure};
    }
    if ( $api->{list_tags} ) {
        $data{'list_tags'} = [split( /\s*,\s*/, $api->{list_tags})];
    }
    if ( $api->{fields} ) {
        $data{fields} = $api->{fields};
    }
    $data{parameters} = $api->{parameters};
    return \%data;
}

sub init {
    load_cat('data/cat_list.json');
    load_api('data/api_list.json');
    load_param('data/api_params.json');
    load_fields('data/api_fields.json');
    load_secure('data/api_secure.json');
    load_list_tags('data/api_list_tags.json');
}

sub load_cat {
    my $file= shift;
    my $data = decode_json(file($file)->slurp());
    my $sth;
    foreach my $row ( @$data ) {
        my ($stmt, @binds) = $sql->insert('cat', $row);
        $sth ||= $dbh->prepare($stmt);
        $sth->execute(@binds);
    }
    my $max = max( map {$_->{cat_id}} @$data );
    $dbh->do('ALTER TABLE `cat` AUTO_INCREMENT = ' . $max);
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
    my $max = max( map { keys %{$_} } values %$data);
    $dbh->do('ALTER TABLE `api` AUTO_INCREMENT = ' . $max);
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

sub load_fields {
    my $file = shift;
    my $data = decode_json(file($file)->slurp());
    my $sth;
    my $stmt = $sql->select('api', ['api_id', 'api_name']);
    my $api_db = $dbh->selectall_hashref($stmt, 'api_name');
    foreach my $api_name ( keys %$data ) {
        foreach my $fields_name ( keys %{$data->{$api_name}} ) {
            my %row = (
                'api_id' => $api_db->{$api_name}{api_id},
                'fields_name' => $fields_name,
                'fields_value' => join(',', @{$data->{$api_name}{$fields_name}})
            );
            my ($stmt, @binds) = $sql->insert('fields', \%row);
            $sth ||= $dbh->prepare($stmt);
            $sth->execute(@binds);
        }
    }
}

sub load_secure{
    my $file = shift;
    my $data = decode_json(file($file)->slurp());
    my $sth;
    foreach my $api_name( keys %$data ) {
        my ($stmt, @binds) = $sql->update(
            'api', {'is_secure' => $data->{$api_name}},
            {'api_name' => $api_name} );
        $sth ||= $dbh->prepare($stmt);
        $sth->execute(@binds);
    }
}

sub load_list_tags{
    my $file = shift;
    my $data = decode_json(file($file)->slurp());
    my $sth;
    foreach my $api_name( keys %$data ) {
        my ($stmt, @binds) = $sql->update(
            'api', {'list_tags' => join(',', @{$data->{$api_name}}) },
            {'api_name' => $api_name} );
        $sth ||= $dbh->prepare($stmt);
        $sth->execute(@binds);
    }
}
