use Net::Top::Request::Base::Shop;
package Net::Top::Request::Shop;
sub get {
   my $pkg = shift;
   return Net::Top::Request::Shop::Get->new(@_);
}

sub showCaseRemainCount {
   my $pkg = shift;
   return Net::Top::Request::Shop::ShowCaseRemainCount->new(@_);
}

sub update {
   my $pkg = shift;
   return Net::Top::Request::Shop::Update->new(@_);
}

package Net::Top::Request::Shop::Get;
our @ISA = ('Net::Top::Request::Base::Shop::Get');

package Net::Top::Request::Shop::ShowCaseRemainCount;
our @ISA = ('Net::Top::Request::Base::Shop::ShowCaseRemainCount');

package Net::Top::Request::Shop::Update;
our @ISA = ('Net::Top::Request::Base::Shop::Update');

1;
