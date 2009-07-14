use Net::Top::Request::Base::Product;
package Net::Top::Request::Product;
sub get {
   my $pkg = shift;
   return Net::Top::Request::Product::Get->new(@_);
}

sub search {
   my $pkg = shift;
   return Net::Top::Request::Product::Search->new(@_);
}

package Net::Top::Request::Product::Get;
our @ISA = ('Net::Top::Request::Base::Product::Get');

package Net::Top::Request::Product::Search;
our @ISA = ('Net::Top::Request::Base::Product::Search');

1;
