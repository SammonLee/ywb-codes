#!/usr/bin/perl -w
# api_utils.pl --- Api 工具集
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
use Getopt::Long;
use Pod::Usage;
use Config::General;
use Log::Log4perl qw/:easy/;

Log::Log4perl->easy_init();

my $config = new Config::General('db.ini');
my %dbconf = $config->getall;
my $dsn = 'dbi:mysql:' . join(';', map { "$_=$dbconf{$_}" } grep { exists $dbconf{$_} } qw/dbname host/),;
my $dbh = DBI->connect(
    $dsn,
    $dbconf{user}, $dbconf{pass},
);
$dbh->do('set names utf8');

my $sql = SQL::Abstract->new;
my $cmd = shift @ARGV;

if ( $cmd && main->can($cmd) ) {
    main->$cmd();
} else {
    pod2usage( $cmd && "Unknown command $cmd");
}

sub show {
    my $api = shift @ARGV;
    $api = get_api($api);
    print JSON::XS->new->pretty->encode($api), "\n";
}

sub update {
    my $api = shift @ARGV;
    my $data  = decode_json(file('meta', $api.'.json')->slurp());
    my $cat = find_or_create('cat', 'cat_id', { cat_name => $data->{api_type} });
    my ($sth, $stmt, @binds);
    ($stmt, @binds) = $sql->select('api', ['api_id','api_name', 'is_secure', 'list_tags'],
                                   { api_name => $data->{method} });
    $sth = $dbh->prepare($stmt);
    $sth->execute(@binds);
    my $api_id;
    if ( $sth->rows ) {
        $api = $sth->fetchrow_hashref;
        $api_id = $api->{api_id};
        my %changes;
        $data->{is_secure} = !!$data->{is_secure};
        if ( $api->{is_secure} != $data->{is_secure} ) {
            $changes{is_secure} = $data->{is_secure};
        }
        if ( !same_words([split(',', $api->{list_tags}||'')], $data->{list_tags}) ) {
            $changes{list_tags} = join(',', @{$data->{list_tags}});
        }
        if ( %changes ) {
            ($stmt, @binds) = $sql->update('api', {api_id => $api->{api_id}}, \%changes);
            $sth = $dbh->prepare($stmt);
            $sth->execute(@binds);
        }
        ($stmt, @binds) = $sql->delete('fields', {api_id => $api_id});
        $sth = $dbh->prepare($stmt);
        $sth->execute(@binds);
        ($stmt, @binds) = $sql->delete('param', {api_id => $api_id});
        $sth = $dbh->prepare($stmt);
        $sth->execute(@binds);
    } else {
        my %row = (
            'api_name' => $data->{method},
            'cat_id' => $cat->{cat_id},
        );
        if ( $data->{is_secure} ) {
            $row{is_secure} = 1;
        }
        if ( $data->{list_tags} ) {
            $row{list_tags} = join(',', @{$data->{list_tags}});
        }
        ($stmt, @binds) = $sql->insert('api', \%row);
        $sth = $dbh->prepare($stmt);
        $sth->execute(@binds);
        $api_id = $dbh->selectall_arrayref('select last_insert_id()')->[0][0];
    }
    if ( $data->{fields} ) {
        my $sth;
        foreach my $fields_name ( keys %{$data->{'fields'}} ) {
            my %row = (
                'api_id' => $api_id,
                'fields_name' => $fields_name,
                'fields_value' => join(',', @{$data->{'fields'}{$fields_name}})
            );
            my ($stmt, @binds) = $sql->insert('fields', \%row);
            $sth ||= $dbh->prepare($stmt);
            $sth->execute(@binds);
        }
    }
    $sth = undef;
    foreach my $param ( @{$data->{parameters}} ) {
        my %row = (
            'api_id' => $api_id,
        );
        foreach my $name ( keys %$param ) {
            $row{'param_'.$name} = $param->{$name};
        }
        my ($stmt, @binds) = $sql->insert('param', \%row);
        $sth ||= $dbh->prepare($stmt);
        $sth->execute(@binds);
    }
}

sub same_words {
    my ($a, $b) = @_;
    $a ||= [];
    $b ||= [];
    return join(',', sort(@$a)) eq join(',', sort(@$b));
}

sub find_or_create {
    my ($table, $fields, $where, $row) = @_;
    my ($sth, $stmt, @binds);
    ($stmt, @binds) = $sql->select($table, $fields, $where);
    $sth = $dbh->prepare($stmt);
    $sth->execute(@binds);
    if ( $sth->rows ) {
        return $sth->fetchrow_hashref;
    } else {
        $row ||= $where;
        ($stmt, @binds) = $sql->insert($table, $row);
        $sth = $dbh->prepare($stmt);
        $sth->execute(@binds);
        ($stmt, @binds) = $sql->select($table, $fields, $where);
        $sth = $dbh->prepare($stmt);
        $sth->execute(@binds);
        return $sth->fetchrow_hashref;
    }
}

sub export {
    my $stmt = $sql->select('api', 'api_name');
    my $apis = $dbh->selectall_arrayref($stmt);
    my $coder = JSON::XS->new->pretty;
    my $dir = dir('meta');
    if ( !-d $dir ) {
        $dir->mkpath() or die "Can't create dir $dir: $!";
    }
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

__END__

=head1 NAME

api_utils.pl -  api 工具集

=head1 SYNOPSIS

api_utils.pl command

   Commands:
     init            导入数据到数据库（数据表已经创建并为空）
     export          将数据库中 api 信息输出到文件

=cut

