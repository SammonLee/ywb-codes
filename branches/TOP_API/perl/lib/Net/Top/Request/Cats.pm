use Net::Top::Request::Base::Cats;
package Net::Top::Request::Cats;
sub get {
   my $pkg = shift;
   return Net::Top::Request::Cats::Get->new(@_);
}

sub propsGet {
   my $pkg = shift;
   return Net::Top::Request::Cats::PropsGet->new(@_);
}

sub propvaluesGet {
   my $pkg = shift;
   return Net::Top::Request::Cats::PropvaluesGet->new(@_);
}

sub spuGet {
   my $pkg = shift;
   return Net::Top::Request::Cats::SpuGet->new(@_);
}

package Net::Top::Request::Cats::Get;
our @ISA = ('Net::Top::Request::Base::Cats::Get');

package Net::Top::Request::Cats::PropsGet;
our @ISA = ('Net::Top::Request::Base::Cats::PropsGet');

package Net::Top::Request::Cats::PropvaluesGet;
our @ISA = ('Net::Top::Request::Base::Cats::PropvaluesGet');

package Net::Top::Request::Cats::SpuGet;
our @ISA = ('Net::Top::Request::Base::Cats::SpuGet');

1;
