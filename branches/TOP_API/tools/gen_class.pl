#!/usr/bin/perl
# gen_class.pl --- 代码生成工具
# Author: Ye Wenbin <wenbin.ye@alibaba-inc.com>
# Created: 09 Mar 2009
# Version: 0.01

use warnings;
use strict;

use FindBin qw/$Bin/;
use lib "$Bin/../perl/lib";
use JSON::XS;
use Net::Top::Helper;
use Data::Dumper qw(Dumper);
use Path::Class;
use List::MoreUtils qw/uniq/;
use Log::Log4perl qw/:easy/;
use File::Temp qw/tempfile/;
use Getopt::Long;

my $code_dir = "$Bin/../php/src"; # output code directory
my $lang = 'php';                 # generate code for language

GetOptions(
    'lang=s' => \$lang,
    'dir=s' => \$code_dir,
);
my %implement_lang = ( perl => 1, php => 1 );
if ( !$lang || !exists $implement_lang{$lang} ) {
    die "Language?\n";
}
$lang = ucfirst($lang);
if ( !$code_dir ) {
    die "Save directory?\n";
}
if ( !-d $code_dir ) {
    mkdir($code_dir) or die "Can't mkdir '$code_dir': $!\n";
}

Log::Log4perl->easy_init();
$Data::Dumper::Indent=1;
my $dir = dir("$Bin/meta");      # metadata directory

my @apis = @ARGV;
if ( !@apis ) {
    my $dh = $dir->open();
    while ( my $file = $dh->read ) {
        next if $file !~ /\.json$/;
        $file = $dir->file($file);
        if ( -f $file ) {
            (my $api_name = $file->basename) =~ s/\.json$//;
            push @apis, $api_name;
        }
    }
}
foreach my $api_name ( @apis ) {
    gen_class($api_name);
}

sub gen_class {
    my $api_name = shift;
    my $api = get_api($api_name);
    if ( !$api ) {
        die("Unknown api '$api_name'\n");
    }
    my $generator = "Net::Top::Gen::${lang}"; # Language Code Generator
    if ( $api->{fields} ) {
        expand_fields($api);
    }
    my %parameters;
    foreach my $param ( @{$api->{parameters}} ) {
        if ( $param->{'classname'} eq 'isMust' ) {
            push @{$parameters{required}}, $param->{name};
        }
        elsif ( $param->{'classname'} eq 'mSelect' ) {
            push @{$parameters{optional}}, $param->{name};
        } else {
            push @{$parameters{other}}, $param->{name};
        }
        if ( $param->{'type'} eq 'file' ) {
            push @{$parameters{file}}, $param->{name};
            $api->{http_method} = 'post';
        }
    }
    $api->{parameters} = \%parameters;
    my $gen = $generator->new({
        dir => $code_dir,
        class => $api->{class},
        ancestor => 'Net_Top_Request',
        metadata => $api,
    });
    my $base_file = $gen->get_file();
    if ( !-d $base_file->dir ) {
        $base_file->dir->mkpath() or die "Can't make dir" . $base_file->dir;
    }
    my $fh;
    open($fh, ">", $base_file) or die "Can't create file $base_file: $!";
    # $fh = \*STDOUT;
    print {$fh} $gen->get_code();
    close($fh);
    DEBUG("Save code to $base_file");
}

sub get_api {
    my $api_name = shift;
    return decode_json(file('meta', $api_name . '.json')->slurp());
}

sub expand_fields {
    my $data = shift;
    my $expanded = {};
    foreach my $tag ( keys %{$data->{fields}} ) {
        expand_fields2($data->{fields}, $tag, $expanded);
    }
}

sub expand_fields2 {
    my ($fields, $tag, $expanded) = @_;
    if ( exists $expanded->{$tag} ) {
        return;
    }
    my @flat = ();
    foreach my $name ( @{$fields->{$tag}} ) {
        if ( substr($name, 0, 1) eq ':' ) {
            expand_fields2($fields, $name, $expanded);
            push @flat, @{$fields->{$name}};
        } else {
            push @flat, $name;
        }
    }
    @flat = uniq(@flat);
    $fields->{$tag} = \@flat;
    $expanded->{$tag} = 1;
}

sub gen_new_filename {
    my $file = shift;
    my $new_file = $file;
    my $i = 1;
    while ( -e $new_file ) {
        $new_file = $file . '.' . $i;
        $i++;
    }
    return $new_file;
}

__END__

=head1 NAME

gen_class.pl - 代码生成工具

=head1 SYNOPSIS

gen_class.pl [options] [api]

      -l --lang      代码生成的语言，默认为 php
      -d --dir       代码文件保存目录，默认为 ../php/src

=cut
