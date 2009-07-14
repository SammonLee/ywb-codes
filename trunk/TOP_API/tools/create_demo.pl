#!/home/y/bin/perl -w
# create_demo.pl --- 
# Author: Ye Wenbin <wenbin.ye@alibaba-inc.com>
# Created: 06 Mar 2009
# Version: 0.01

use warnings;
use strict;
use Path::Class;
use JSON::XS;

my $ref = decode_json(file("/home/y/share/htdocs/ywb/api/api.json")->slurp());
my $fac = $ref->{factories};
my $pdir = "/home/y/share/htdocs/ywb/api/current_api/";

for my $api( @{$ref->{apis}} ) {
    my $class = $fac->{$api};
    my $subdir = $pdir .'_' . $api;
    my $method = "$class->[1]::$class->[2]";
    mkdir($subdir) if !-d $subdir;
    my $file = "$subdir/demo.php";
    open(my $fh, ">", $file) or die "Can't create file $file: $!";
    my $code = <<'EOC';
<?php
require_once('/home/y/share/htdocs/ywb/api/api_config.inc');
$top = new Apps_Api_TopItem($top_url, $top_appkey, $top_secret);
$req = new Apps_Api_Item_Get();
print_r($top->itemGet($req));
EOC

#     my $code = <<'EOC';
# <?php
# require_once('/home/y/share/htdocs/ywb/api/api_config.inc');
# $top = new Apps_Api_Top($top_url, $top_appkey, $top_secret);
# $req = __METHOD__(
#     array(
#         'fields' => array(':small'),
#         'iid' => $iid,
#         'nick' => $nick,
#     )
# );
# $res = $top->request($req);
# if ( $res->isError() ) {
#     echo $res->message();
# } else {
#     print_r($res->result());
# }
# EOC
    $code =~ s/__METHOD__/$method/;
    print {$fh} $code;
}


__END__

=head1 NAME

create_demo.pl - Describe the usage of script briefly

=head1 SYNOPSIS

create_demo.pl [options] args

      -opt --long      Option description

=head1 DESCRIPTION

Stub documentation for create_demo.pl, 

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
