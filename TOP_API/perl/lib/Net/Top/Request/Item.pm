use Net::Top::Request::Base::Item;
package Net::Top::Request::Item;
sub get {
   my $pkg = shift;
   return Net::Top::Request::Item::Get->new(@_);
}

sub itemsGet {
   my $pkg = shift;
   return Net::Top::Request::Item::ItemsGet->new(@_);
}

package Net::Top::Request::Item::Get;
our @ISA = ('Net::Top::Request::Base::Item::Get');

package Net::Top::Request::Item::ItemsGet;
our @ISA = ('Net::Top::Request::Base::Item::ItemsGet');

1;
