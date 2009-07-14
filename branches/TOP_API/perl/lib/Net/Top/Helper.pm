package Net::Top::Helper;

package Net::Top::Gen::Php;
use Path::Class;
our $prefix = 'Net_Top_Request';

sub get_class_file {
    my ($self, $dir, $pkg) = @_;
    return file($dir, $self->get_class_name($pkg) . '.class.php');
}

sub get_base_class_file {
    my ($self, $dir, $pkg) = @_;
    return file($dir, $self->get_base_class_name($pkg) . '.class.php');
}

sub get_class_name {
    my ($self, $pkg) = @_;
    return $prefix . '_'.$pkg;
}

sub get_base_class_name {
    my ($self, $pkg) = @_;
    return $prefix . '_Base_'. $pkg;
}

sub get_class_code {
    my ($self, $pkg, $subclass) = @_;
    my $class_name = $self->get_class_name($pkg);
    my $base_name = $self->get_base_class_name($pkg);
    my $code = "<?php\n"
        . "class $class_name extends $base_name\n"
        . "{";
    foreach my $name ( sort {$a cmp $b} keys %$subclass) {
        my $method = lcfirst($name);
        $code .= <<EOC;

    static function $method ( \$args = null ) {
         return new ${class_name}_${name}(\$args);
    }
EOC
    }
    $code .= "}\n";
    
    foreach my $name ( sort {$a cmp $b} keys %$subclass ) {
        $code .= <<EOC;

class ${class_name}_$name extends ${base_name}_$name 
{
}
EOC
    }
    return $code;
}

sub get_base_class_code {
    my ($self, $pkg, $subclass) = @_;
    my $base_name = $self->get_base_class_name($pkg);
    my $code = <<EOC;
<?php
class $base_name
{
}

EOC
    
    foreach my $name ( sort {$a cmp $b} keys %$subclass) {
        my $class = "${base_name}_${name}";
        my $api = $subclass->{$name};
        delete $api->{class};
        if ( $api->{http_method} && $api->{http_method} eq 'get' ) {
            delete $api->{http_method};
        }
        $code .= <<EOC;
class $class extends Net_Top_Request
{
    static \$meta_data = ${\( php_var_dump($api, 1) )};
}
Net_Top_Request::cookData(${class}::\$meta_data);

EOC
    }
    return $code;
}

sub php_var_dump {
    my ($var, $level) = @_;
    my $indent = ' ' x 4;
    my $indent0 = $indent x $level;
    my $indent1 = $indent0 . $indent;
    my $str = '';
    if ( ref $var eq 'HASH' ) {
        $str .= "array(";
        for ( keys %$var ) {
            $str .= "\n" . $indent1 . php_value($_) . ' => ' . php_var_dump($var->{$_}, $level+1) . ",";
        }
        $str .= "\n" . $indent0 . ")";
    } elsif ( ref $var eq 'ARRAY' ) {
        $str .= "array(";
        foreach ( @$var ) {
            $str .= "\n" . $indent1 . php_var_dump($_, $level) . ",";
        }
        $str .= "\n" . $indent0 . ")";
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

package Net::Top::Gen::Perl;
use Path::Class;

our $prefix = "Net::Top::Request";

sub get_class_file{
    my ($self, $dir, $pkg) = @_;
    return file($dir, split('::', $self->get_class_name($pkg).'.pm'));
}

sub get_base_class_file{
    my ($self, $dir, $pkg) = @_;
    return file($dir, split('::', $self->get_base_class_name($pkg).'.pm'));
}

sub get_class_name{
    my ($self, $pkg) = @_;
    return $prefix .'::'. $pkg;
}

sub get_base_class_name{
    my ($self, $pkg) = @_;
    return $prefix . '::Base::' . $pkg;
}

sub get_class_code {
    my ($self, $pkg, $subclass) = @_;
    my $class_name = $self->get_class_name($pkg);
    my $base_name = $self->get_base_class_name($pkg);
    my $code = <<EOC;
use $base_name;
package $class_name;
EOC
    foreach my $name ( sort {$a cmp $b} keys %$subclass) {
        my $method = lcfirst($name);
        $code .= <<EOC;
sub $method {
   my \$pkg = shift;
   return ${class_name}::$name->new(\@_);
}

EOC
                                                          }
    foreach my $name ( sort {$a cmp $b} keys %$subclass ) {
        $code .= <<EOC;
package ${class_name}::$name;
our \@ISA = ('${base_name}::$name');

EOC
    }
    $code .= "1;\n";
    return $code;
}

sub get_base_class_code {
    my ($self, $pkg, $subclass) = @_;
    my $base_name = $self->get_base_class_name($pkg);
    my $code = '';
    
    foreach my $name ( sort {$a cmp $b} keys %$subclass) {
        my $api = $subclass->{$name};
        delete $api->{class};
        if ( $api->{http_method} && $api->{http_method} eq 'get' ) {
            delete $api->{http_method};
        }
        my $var = Data::Dumper->new([$api])->Terse(1)->Indent(1)->Dump();
        $var =~ s/\s*{\s*(.*)\s*}\s*/$1/sm;
        $code .= <<EOC;
package ${base_name}::${name};
use base '$prefix';

__PACKAGE__->make_request(
  $var);

EOC
    }
    $code .= "1;\n";
    return $code;
}

1;
__END__

=head1 NAME

gen_perl - Perl extension for blah blah blah

=head1 SYNOPSIS

   use gen_perl;
   blah blah blah

=head1 DESCRIPTION

Stub documentation for gen_perl, 

Blah blah blah.

=head2 EXPORT

None by default.

=head1 SEE ALSO

Mention other useful documentation such as the documentation of
related modules or operating system documentation (such as man pages
in UNIX), or any relevant external documentation such as RFCs or
standards.

If you have a mailing list set up for your module, mention it here.

If you have a web site set up for your module, mention it here.

=head1 AUTHOR

Ye Wenbin, E<lt>wenbinye@gmail.comE<gt>

=head1 COPYRIGHT AND LICENSE

Copyright (C) 2009 by Ye Wenbin

This program is free software; you can redistribute it and/or modify
it under the same terms as Perl itself, either Perl version 5.8.2 or,
at your option, any later version of Perl 5 you may have available.

=head1 BUGS

None reported... yet.

=cut
