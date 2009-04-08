#!/home/y/bin/perl -w
# gen_perl_class.pl --- 
# Author: Ye Wenbin <wenbin.ye@alibaba-inc.com>
# Created: 09 Mar 2009
# Version: 0.01

use warnings;
use strict;

use JSON::XS;
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
    my @r = split /_/, $factory;
    my $pkg = pop(@r);
    my $class_name = 'Net::Top::Request::' . $pkg;
    my $file = $pkg . ".pm";
    # open(my $fh, ">", $file) or die "Can't create file $file: $!";
    # select($fh);
    my $subclass = $classes{$factory};
    print <<EOC;
package $class_name;
use base 'Net::Top::Request::Factory';
EOC
    foreach my $name ( sort {$a cmp $b} keys %$subclass) {
        my $method = lcfirst($name);
        print <<EOC;
sub $method {
   my \$pkg = shift;
   return \$pkg->factory('${class_name}::$name', \@_);
}

EOC
    }

    foreach my $name ( sort {$a cmp $b} keys %$subclass) {
        my $class = "${class_name}::${name}";
        my $api = $subclass->{$name};
        delete $api->{class};
        if ( $api->{http_method} && $api->{http_method} eq 'get' ) {
            delete $api->{http_method};
        }
        my $var = Data::Dumper->new([$api])->Terse(1)->Indent(1)->Dump();
        $var =~ s/\s*{\s*(.*)\s*}\s*/$1/sm;
        print <<EOC;
package $class;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  $var);

EOC
    }
    print "1;\n";
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
