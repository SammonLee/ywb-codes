#!/home/y/bin/perl -w
# gen_class2.pl --- 
# Author: Ye Wenbin <wenbin.ye@alibaba-inc.com>
# Created: 09 Mar 2009
# Version: 0.01

use warnings;
use strict;

use lib '/home/ywb/proj/ywb-codes/branches/TOP_API/perl/lib';
use FindBin qw/$Bin/;
use JSON::XS;
use Net::Top::Helper;
use Data::Dumper qw(Dumper);
use Path::Class;
use List::MoreUtils qw/uniq/;
use Log::Log4perl qw/:easy/;
use File::Temp qw/tempfile/;
use Getopt::Long;

@ARGV = qw(-l php -d /tmp/php -t Item);

my ($api_types,                 # generate code for given type of api 
    $code_dir,                  # output code directory
    $lang);                     # generate code for language
GetOptions(
    'lang=s' => \$lang,
    'dir=s' => \$code_dir,
    'type=s' => \$api_types,
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
my $dir = "$Bin/api_meta";      # metadata directory

# %metadata store all api meta data.
# The key of %metadata is api type.
# There is known 6 type of api:
#  - Item
#  - Cat
#  - Product
#  - Shop
#  - Trade
#  - Shipping
#
# The value of %metadata is an array of apis belongs to that api type.
my %metadata;
while ( <$dir/*.json> ) {
    eval {
        my $api = decode_json(file($_)->slurp());
        push @{$metadata{$api->{api_type}}}, $api;
    };
    if ( $@ ) {
        WARN("Load api $_ failed: $@");
    }
}

my %api_types;                  # all wanted classed
if ( $api_types ) {
    %api_types = map {$_=>1} split /,/, $api_types;
}
my $generator = "Net::Top::Gen::${lang}"; # Language Code Generator
foreach my $api_type ( keys %metadata ) {
    if ( $api_types && !exists $api_types{$api_type} ) {
        # exclude not wanted class
        next;
    }
    # ok, generate the code for class
    foreach my $api( @{$metadata{$api_type}} ) {
        expand_fields($api);
        # print Dumper($api), "\n";
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
