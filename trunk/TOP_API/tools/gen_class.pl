#!/home/y/bin/perl -w
# gen_perl_class.pl --- 
# Author: Ye Wenbin <wenbin.ye@alibaba-inc.com>
# Created: 09 Mar 2009
# Version: 0.01

use warnings;
use strict;

use lib 'd:/SVN/ywb-codes/TOP_API/perl/lib';
use FindBin qw/$Bin/;
use JSON::XS;
use Net::Top::Helper;
use Data::Dumper qw(Dumper);
use Path::Class;
use Log::Log4perl qw/:easy/;
use File::Temp qw/tempfile/;
use Getopt::Long;

my ($all, $for_class, $code_dir, $lang);
GetOptions(
    'lang=s' => \$lang,
    'dir=s' => \$code_dir,
    'all' => \$all,
    'class=s' => \$for_class,
);
my %implement_lang = ( perl => 1, php => 1 );
if ( !$lang || !exists $implement_lang{$lang} ) {
    die "Language?\n";
}
$lang = ucfirst($lang);
if ( !$code_dir ) {
    die "Save directory?\n";
}

Log::Log4perl->easy_init();
$Data::Dumper::Indent=1;
my $dir = "$Bin/api_meta";
my %classes;
while ( <$dir/*.json> ) {
    eval {
        my $api = decode_json(file($_)->slurp());
        my $class = $api->{class};
        my ($factory, $method) = ($class =~ /(.*)_(\w+)$/);
        $classes{$factory}{$method} = $api;
    };
    if ( $@ ) {
        WARN("Load api $_ failed: $@");
    }
}

my %for_class;
if ( $for_class ) {
    %for_class = map {$_=>1} split /,/, $for_class;
}
my $gen = "Net::Top::Gen::${lang}";
foreach my $factory ( keys %classes ) {
    my @r = split /_/, $factory;
    my $pkg = pop(@r);
    if ( $for_class && !exists $for_class{$pkg} ) {
        next;
    }

    my $subclass = $classes{$factory};
    my $fh;
    if ( $all ) {
        my $file = file($gen->get_class_file($code_dir, $pkg));
        if ( !-d $file->dir ) {
            $file->dir->mkpath() or die "Can't make dir" . $file->dir;
        }
        if ( -e $file ) {
            my $new_file = gen_new_filename($file);
            INFO("Save old $file to $new_file");
            rename($file, $new_file);
        }
        open($fh, ">", $file) or die "Can't create file $file: $!";
        print {$fh} $gen->get_class_code($pkg, $subclass);
        # print $gen->get_class_code($pkg, $subclass);
    }

    my $base_file = file($gen->get_base_class_file($code_dir, $pkg));
    if ( !-d $base_file->dir ) {
        $base_file->dir->mkpath() or die "Can't make dir" . $base_file->dir;
    }
    open($fh, ">", $base_file) or die "Can't create file $base_file: $!";
    print {$fh} $gen->get_base_class_code($pkg, $subclass);
    # print $gen->get_base_class_code($pkg, $subclass);
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

generate_class.pl - Describe the usage of script briefly

=head1 SYNOPSIS

generate_class.pl [options] args

      -opt --long      Option description

=head1 DESCRIPTION

Stub documentation for generate_class.pl, 

=head1 AUTHOR

Ye Wenbin, E<lt>wenbin.ye@alibaba-inc.comE<gt>

=head1 COPYRIGHT AND LICENSE

Copyright (C) 2009 by Ye Wenbin

This program is free software; you can redistribute it and/or modify
it under the same terms as Perl itself, either Perl version 5.8.2 or,
at your option, any later version of Perl 5 you may have available.

=head1 BUGS

None reported... yet.

=cut
