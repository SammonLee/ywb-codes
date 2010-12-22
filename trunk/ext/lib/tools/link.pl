#!/usr/bin/perl -w
# link.pl --- 
# Author: Ye Wenbin <wenbinye@gmail.com>
# Created: 24 Oct 2010
# Version: 0.01

use warnings;
use strict;
use File::Basename;
use File::Spec::Functions;
use Getopt::Long;
use Regexp::Common;
use File::stat;
use Data::Dumper qw(Dumper);

my $outfile;
my $outdir;
my $browser;
my $resource_alias='';
my $include_dir = '';
my $ext = 'js';
my $force;
my @INCLUDE_PATH;

GetOptions(
    'output=s' => \$outfile,
    'dir=s' => \$outdir,
    'browser=s' => \$browser,
    'extension=s' => \$ext,
    'resource=s' => \$resource_alias,
    'include=s' => \$include_dir,
    'force' => \$force,
);
add_include_path(split ':', $include_dir);

foreach my $file ( @ARGV ) {
    if ( -f $file ) {
        my $out;
        if ( $outfile ) {
            $out = ($outfile eq '-' ? undef : $outfile);
        } else {
            my ($basename) = fileparse($file);
            if ( $outdir ) {
                $out = catfile($outdir, $basename);
            } else {
                $out = catfile(dirname($file), $basename . 'linked');
            }
        }
        link_file($file, $out);
    } elsif ( -d $file ) {
        if ( !$outdir ) {
            die "Process directory should given output directory\n";
        }
        opendir(DIR, $file) or die "Can't open directory $file: $!";
        my $re = quotemeta('.'.$ext);
        while ( my $f = readdir(DIR) ) {
            $f = catfile($file, $f);
            if ( -f $f && $f =~ /$re$/ ) {
                my ($filename) = fileparse($f);
                link_file($f, catfile($outdir, $filename));
            }
        }
    }
}

sub sanitize_extension {
    my $file = shift;
    $file =~ s/\..*+$//;
    return $file;
}

sub file_newer {
    my ($f1, $f2) = @_;
    my $s1 = stat($f1);
    return 0 if !defined($s1);
    my $s2 = stat($f2);
    return 0 if !defined($s2);
    return $s1->mtime >= $s2->mtime;
}

sub link_file {
    my ($file, $outfile) = @_;
    if ( !$force && file_newer($outfile, $file) ) {
        return;
    }
    DEBUG("link '$file' to '".($outfile||'')."'");
    open(my $fh, "<", $file) or die "Can't open file $file: $!";
    my $out;
    if ( $outfile ) {
        open($out, ">", $outfile) or die "Can't create file $outfile: $!";
    } else {
        $out = \*STDOUT;
    }
    my ($backup_fh, $null, $null_str);
    open($null, ">", \$null_str) or die "Can't open null fh: $!";
    my $dir = dirname($file);
    add_include_path($dir);
    while ( <$fh> ) {
        if ( /\/\/ \@include\s+(\S+)/ && $1 !~ /^http:\/\// ) {
            my $file = get_include_file($1);
            if ( $file ) {
                print {$out} slurp($file), "\n";
            } else {
                DEBUG("Failed to " . $_);
            }
        } elsif ( /\/\/ \@if\s+(\S+)/) {
            if ( index($1, $browser) == -1 ) {
                $backup_fh = $out;
                $out = $null;
            }
        } elsif ( /\/\/ \@endif/ ) {
            if ( $out == $null ) {
                $out = $backup_fh;
            }
        } elsif ( /(\s*)\/\/ \@import\s*(.*)/ ) {
            if ( $browser eq 'firefox' ) {
                my $indent = $1;
                my $c = $2;
                if ( $c =~ /^\s*($RE{balanced}{-parens=>'()'})/ ) {
                    print {$out} $indent, trim(substr($1, 1, -1)), "\n";
                } elsif ( $c =~ /^([\w-]+)/) {
                    my $module = $1;
                    if ( $module eq 'util' ) {
                        print {$out} $indent, qq|Components.utils.import("resource://$resource_alias/util.js", S); S.exports.extend(S, S.exports);\n|;
                    } else {
                        my $module_name = index($module,'-') != -1
                            ? join('', map(ucfirst, split('-', $module)))
                                : $module;
                        print {$out} $indent, qq|Components.utils.import("resource://$resource_alias/$module.js", S); S.$module_name = S.exports.$module_name;\n|;
                    }
                }
            }
        } else {
            print {$out} $_;
        }
    }
    remove_include_path($dir);
}

sub add_include_path {
    my %path = map { $_ => 1 } @INCLUDE_PATH;
    unshift @INCLUDE_PATH, grep { -d $_ && !exists $path{$_} } map { File::Spec->rel2abs(File::Spec->canonpath($_)) } @_;
}

sub remove_include_path {
    my %path = map { $INCLUDE_PATH[$_] => $_ } 0..$#INCLUDE_PATH;
    for my $p( map { File::Spec->rel2abs(File::Spec->canonpath($_)) } @_ ) {
        if ( exists $path{$p} ) {
            delete $path{$p};
        }
    }
    @INCLUDE_PATH = sort { $path{$a} <=> $path{$b} } keys %path;
}

sub get_include_file {
    my $name = shift;
    for ( @INCLUDE_PATH ) {
        my $file = File::Spec->catfile($_, $name);
        if ( -r $file ) {
            return $file;
        }
    }
}

sub trim {
    my $str = shift;
    $str =~ s/^\s*//;
    $str =~ s/\s*$//;
    return $str;
}

sub slurp{
    my $file = shift;
    if ( -r $file ) {
        local $/ = undef;
        open(my $fh, "<", $file) or die "Can't open file $file: $!";
        return <$fh>;
    }
}

sub DEBUG{
    print STDERR @_, "\n";
}

__END__

=head1 NAME

link.pl - Browser extension development code generation util

=head1 SYNOPSIS

link.pl [options] [files]

      -i --include      search @include files in these directories. Seperate multiple directories by colon(:)
      -b --browser      extension browser type. support chrome, safari, firefox, opera
      -o --output       output generated code to this file. '-' output to standard output.
      -d --dir          output generated file to this directory.
      -e --extension    file name extension
      -r --resource     firefox resource alias
      -f --force        force to generate file

=head1 DESCRIPTION

Stub documentation for link.pl, 

=head1 AUTHOR

Ye Wenbin, E<lt>wenbinye@gmail.comE<gt>

=head1 COPYRIGHT AND LICENSE

Copyright (C) 2010 by Ye Wenbin

This program is free software; you can redistribute it and/or modify
it under the same terms as Perl itself, either Perl version 5.8.2 or,
at your option, any later version of Perl 5 you may have available.

=head1 BUGS

None reported... yet.

=cut
