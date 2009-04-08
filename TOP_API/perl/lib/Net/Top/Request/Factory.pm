package Net::Top::Request::Factory;
sub factory {
    my $pkg = shift;
    my $class = shift;
    my %params = @_;
    my $req = $class->new;
    for ( keys %params ) {
        $req->set($_, $params{$_});
    }
    return $req;
}

1;
