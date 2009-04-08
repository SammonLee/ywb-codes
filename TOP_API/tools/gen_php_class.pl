#!/home/y/bin/perl -w
# gen_php_class.pl --- 
# Author: Ye Wenbin <wenbin.ye@alibaba-inc.com>
# Created: 09 Mar 2009
# Version: 0.01

use warnings;
use strict;

use JSON::XS;
use YAML;
use Data::Dumper qw(Dumper);
use Path::Class;
use File::Temp qw/tempfile/;

my $dir = 'api_meta';

my %classes;
while ( <$dir/*.json> ) {
    my $api = decode_json(file($_)->slurp());
    my $class = $api->{class};
    my ($factory, $method) = ($class =~ /(.*)_(\w+)$/);
    $classes{$factory}{$method} = $api;
}

foreach my $factory ( keys %classes ) {
    # my ($fh, $filename) = tempfile();
    # select($fh);
    my $subclass = $classes{$factory};
    print <<EOC;
<?php
class $factory
{
EOC
    foreach my $name ( sort {$a cmp $b} keys %$subclass) {
        my $method = lcfirst($name);
        print <<EOC;
    static function $method ( \$args = null ) {
         return new ${factory}_${name}(\$args);
    }

EOC
    }
    print "}\n\n";

    foreach my $name ( sort {$a cmp $b} keys %$subclass) {
        my $class = "${factory}_${name}";
        my $api = $subclass->{$name};
        delete $api->{class};
        if ( exists $api->{http_method} && $api->{http_method} eq 'get' ) {
            delete $api->{http_method};
        }
        print <<EOC;
class $class extends Net_Top_Request
{
    static \$meta_data = ${\( php_var_dump($api) )};
}
Net_Top_Request::cookData(${class}::\$meta_data);

EOC
    }
    # close($fh);
    # system("$phpcb $filename > $dir/$factory.class.php");
}

sub php_var_dump {
    my $var = shift;
    my $str = '';
    if ( ref $var eq 'HASH' ) {
        $str .= "array(\n";
        for ( keys %$var ) {
            $str .= php_value($_) . ' => ' . php_var_dump($var->{$_}) . ",\n";
        }
        $str .= ")";
    } elsif ( ref $var eq 'ARRAY' ) {
        $str .= "array(\n";
        foreach ( @$var ) {
            $str .= php_var_dump($_) . ",\n";
        }
        $str .= ")";
    } else {
        $str .= php_value($var);
    }
    return $str;
}

sub php_value {
    my $var = shift;
    if ( !defined($var) ) {
        return 'null';
    }
    return Data::Dumper->new([$var])->Terse(1)->Indent(0)->Dump();
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
