package Farsail::Log;

use strict; 
use warnings;

use Carp;
use Log::Log4perl qw/:easy/;

sub init {
    my ($pkg, $farsail) = @_;
    my $logger = Log::Log4perl->get_logger('Farsail');
    $farsail->addProperty('logger', $logger);
    $farsail->addMethod(
        'log', sub {
            my $self = shift;
            $logger->info(@_);
        });
    my $dispatcher = $farsail->getEventDispatcher();
    $dispatcher->connect(
        'farsail.beforeCallAction',
        sub {
            my $event = shift;
            my $action = $event->getSubject();
            if ( $action->getName() ne 'help' ) {
                $logger->info($action->getFullName() . ' START');
            }
            return 1;
        });
    $dispatcher->connect(
        'farsail.afterCallAction',
        sub {
            my $event = shift;
            my $action = $event->getSubject();
            if ( $action->getName() ne 'help' ) {
                $logger->info($action->getFullName() . ' END');
            }
            return 1;
        });
    $dispatcher->connect(
        'farsail.skipCallAction',
        sub {
            my $event = shift;
            my $action = $event->getSubject();
            if ( $action->getName() ne 'help' ) {
                $logger->info($action->getFullName() . ' SKIPPED');
            }
            return 1;
        });
}

1;
