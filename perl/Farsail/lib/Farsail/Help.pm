package Farsail::Help;

use strict; 
use warnings;

use Carp;

sub init {
    my ($pkg, $farsail) = @_;
    $farsail->getActions->addActions({
        'global' => {
            'help' => {
                'module' => 'Farsail::Help'
                }
            },
    });
}

sub ACTION_help {
    my ($pkg, $farsail) = @_;
    my $args = $farsail->getActionArgs();
    my $actions = $farsail->getActions;
    my %namespaces = map { $_ => 1 } $actions->getNamespaces;
    if ( @$args ) {
        for ( @$args ) {
            if ( (my $action = $actions->getAction($_)) ) {
                $pkg->describeAction($action);
            } elsif ( exists $namespaces{$_} ) {
                $pkg->describeNamespace($_, $actions);
            }
        }
    } else {
        for ( keys %namespaces ) {
            $pkg->describeNamespace($_, $actions);
        }
    }
}

sub describeAction {
    my ($pkg, $action) = @_;
    print "Full name:\n\t", $action->getFullName(), "\n";
    print "Name:\n\t", $action->getName(), "\n";
    my $depends = $action->getAllDepends();
    my %modules;
    $modules{$action->getModule()}++;
    if ( @$depends) {
        foreach ( @$depends ) {
            $modules{$_->getModule()}++;
        }
    }
    print "Require modules:\n", join("\n", map { "\t". $_} sort keys %modules), "\n";
    my $args = $action->getAllArgs();
    if ( %$args ) {
        print "Arguments:\n";
        for ( sort keys %$args ) {
            my $arg = $args->{$_};
            print "\t$_: ", join(", ", map {
                "$_=$arg->{$_}"
            } grep {exists $arg->{$_} } qw/type default require/), "\n";
        }
    }
    if ( @$depends ) {
        print "Runing Order:\n" . join("\n", map { $_->getFullName() } @$depends), "\n";
    }
}

sub describeNamespace {
    my ($pkg, $namespace, $actions) = @_;
    my @acts = $actions->getNamespaceActions($namespace);
    if ( @acts ) {
        print "$namespace:\n";
        for ( @acts ) {
            print "\t", (ref $_ ? $_->getName() : $_),"\n";
        }
        print "\n";
    }
}

1;
__END__

=head1 NAME

Farsail::Help - implement help system

=head1 SYNOPSIS

   use Farsail;

   Farsail->createInstance(
       plugins => [ 'Farsail::Help' ],
   );

=head1 DESCRIPTION

=cut
