use Net::Top::Request::Base::Item;

package Net::Top::Request::Item;
sub get {
    my $pkg = shift;
    return Net::Top::Request::Item::Get->new(@_);
}

package Net::Top::Request::Item::Get;

use base "Net::Top::Request::Base::Item::Get";

1;
