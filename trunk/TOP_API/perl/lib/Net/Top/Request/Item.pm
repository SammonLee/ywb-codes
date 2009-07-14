use Net::Top::Request::Base::Item;
package Net::Top::Request::Item;
sub get {
   my $pkg = shift;
   return Net::Top::Request::Item::Get->new(@_);
}

sub instockGet {
   my $pkg = shift;
   return Net::Top::Request::Item::InstockGet->new(@_);
}

sub itemsGet {
   my $pkg = shift;
   return Net::Top::Request::Item::ItemsGet->new(@_);
}

sub onsaleGet {
   my $pkg = shift;
   return Net::Top::Request::Item::OnsaleGet->new(@_);
}

package Net::Top::Request::Item::Get;
our @ISA = ('Net::Top::Request::Base::Item::Get');

package Net::Top::Request::Item::InstockGet;
our @ISA = ('Net::Top::Request::Base::Item::InstockGet');

package Net::Top::Request::Item::ItemsGet;
our @ISA = ('Net::Top::Request::Base::Item::ItemsGet');

package Net::Top::Request::Item::OnsaleGet;
our @ISA = ('Net::Top::Request::Base::Item::OnsaleGet');

1;
