package Farsail;

use strict; 
use warnings;
use Scalar::Util qw/blessed/;
use Farsail::Event;
use Farsail::EventDispatcher;
use Farsail::Config;
use Farsail::Args;
use Farsail::ActionSet;

use Carp;

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = {};
    bless $self, $class;
    my %opts = @_;
    $self->setEventDispatcher($opts{event_dispatcher})
         ->setConfig($opts{config})
         ->setActions($opts{actions})
         ->setArgs($opts{args});
    $self->loadPlugins($opts{plugins});
    if ( exists $opts{namespace} ) {
        $self->{actions}->addNamespace( ref $opts{namespace} eq 'ARRAY' ? @{$opts{namespace}} : $opts{namespace});
    }
    return $self;
}

sub setEventDispatcher {
    my $self = shift;
    my $dispatcher = shift;
    $self->{dispatcher} = blessed($dispatcher) ? $dispatcher : new Farsail::EventDispatcher($dispatcher);
    return $self;
}

sub getEventDispatcher{
    return shift->{dispatcher};
}

sub setConfig {
    my $self = shift;
    my $config = shift;
    $self->{config} = blessed($config) ? $config : new Farsail::Config($config);
    return $self;
}

sub getConfig {
    return shift->{config};
}

sub setActions{
    my $self = shift;
    my $actions = shift;
    $self->{actions} = blessed($actions) ? $actions : new Farsail::ActionSet($actions || $self->{config}->get('actions'));
    return $self;
}

sub getActions{
    return shift->{actions};
}

sub setArgs{
    my $self = shift;
    my $args = shift;
    $self->{args} = new Farsail::Args();
    if ( $self->{config}->get('argments') ) {
        $self->{args}->define(split /\s*,\s*/, $self->{config}->get('argments'));
    }
    if ( ref $args ne 'ARRAY' ) {
        $args = [ @ARGV ];
    }
    $self->{args}->getopt('pass_through', $args);
    $self->{action_args} = $args;
    return $self;
}

sub getArgs{
    return shift->{args};
}

sub getActionArgs {
    return shift->{action_args};
}

sub setAction {
    my ($self, $action) = @_;
    if ( (my $farsail_action = $self->{actions}->getAction($action)) ) {
        $self->{action} = $farsail_action;
    } else {
        die("Unknown action '$action'");
    }
    return $self;
}

sub getAction {
    return shift->{action};
}

sub setCurrentAction {
    my $self = shift;
    $self->{current_action} = shift;
    return $self;
}

sub getCurrentAction {
    return shift->{current_action};
}

sub loadPlugins {
    my ($self, $plugins) = @_;
    if ( ref $plugins eq 'ARRAY' ) {
        for ( @$plugins ) {
            $self->loadModule($_);
        }
    }
}

sub getInstance {
    my $class = shift;
    return $class if ref $class;
    my $instance = ${ $class.'::_instance' };
    if ( !defined($instance) ) {
        die("Instance is not initialized");
    }
    return $instance;
}

sub createInstance {
    my $class = shift;
    return $class if ref $class;
    no strict 'refs';
    my $instance = ${ $class.'::_instance' } = $class->new(@_);

    $instance->{dispatcher}->notify(new Farsail::Event(
        name => 'farsail.createInstance',
    ));
    return $instance;
}

sub initActionArgs {
    my $self = shift;
    my $args = $self->{args};
    my $args_defs = $self->{action}->getAllArgs();
    foreach my $name ( keys %$args_defs ) {
        $args->define($name, $args_defs->{$name});
    }
    $args->getopt($self->{action_args});
}

sub dispatch {
    my $self = shift;
    if ( !$self->{action} ) {
        if ( @{$self->{action_args}} ) {
            my $args = $self->{action_args};
            if ( $self->{actions}->hasAction($args->[0]) ) {
                $self->setAction(shift @$args);
            }
        }
        if ( !$self->{action} ) {
            confess "No action specified";
        }
    }
    $self->{completed_actions} = {};
    $self->initActionArgs();
    $self->{dispatcher}->notify(new Farsail::Event(
        'name' => 'farsail.beforeDispatch'
    ));
    $self->callAction($self->{action});
    $self->{dispatcher}->notify(new Farsail::Event(
        'name' => 'farsail.afterDispatch'
    ));
}

sub callAction {
    my ($self, $action) = @_;
    if ( exists $self->{completed_actions}{$action->getFullName()} ) {
        return;
    }
    $self->{completed_actions}{$action->getFullName()}++;
    $self->setCurrentAction($action);
    my $event = new Farsail::Event(
        'name' => 'farsail.beforeCallAction',
        'subject' => $action,
    );
    $self->{dispatcher}->filter($event, 1);
    for my $dep( $action->getDepends() ) {
        $self->callAction( $dep );
    }
    if ( $event->getReturnValue() ) {
        $self->loadModule( $action->getModule() );
        $action->call( $self );
    } else {
        $self->{dispatcher}->notify(new Farsail::Event(
            'name' => 'farsail.skipCallAction',
            'subject' => $action
        ));
    }
    $self->{dispatcher}->notify(new Farsail::Event(
        'name' => 'farsail.afterCallAction',
        'subject' => $action
    ));
}

sub loadModule {
    my ($self, $module) = @_;
    if ( ref $module ) {  # load module only by name
        return;
    }
    if ( exists $self->{INC}{$module} ) {
        return;
    }
    $self->{INC}{$module}++;
    if ( !$module->can('can') ) { # module is loaded
        eval("require $module");
        if ( $@ ) {
            confess "Require $module failed: $@\n";
        }
    }
    if ( $module->can('init') ) {
        $module->init($self);
    }
}

sub addProperty {
    my ($self, $name, $obj) = @_;
    no strict 'refs';
    my $class = ref $self || $self;
    *{$class."::$name"} = sub {
        my $self = shift;
        if ( @_ ) {
            $obj = shift;
        }
        return $obj;
    };
}

sub addMethod {
    my ($self, $name, $sub) = @_;
    if ( ref $sub eq 'CODE' ) {
        no strict 'refs';
        my $class = ref $self || $self;
        *{$class . "::$name"} = $sub;
    } else {
        die("Not a callback");
    }
}

1;

__END__

=head1 NAME

Farsail - A lightweight flexible application framework

=head1 SYNOPSIS

  use Farsail;
  Farsail->createInstance(
     actions => {
        'demo' => {
           'module' => 'Demo',
        }
     },
     args => ['hello']
  )->dispatch();

  package Demo;
  sub ACTION_hello {
     print "Hello, Farsail!\n";
  }
  1;

=head1 DESCRIPTION

=head1 METHODS

=over 4

=item createInstance( options )

Create application context instance.

Available options :
  event_dispatcher
  config
  args
  actions
  plugins

=item getInstance()

Get application context instance.

=item setAction( Farsail::Action $action )

Set action to execute.

=item dispatch()

run action.

=item setConfig( Farsail::Config $config )

Set config object.

=item getConfig()

Get config object.

=item setArgs( $args )

Set arguments.

=item getArgs()

get Farsail::Args object.

=item setActions( Farsail::ActionSet $actions )

set action set.

=item getActions()

get action set.

=item getAction()

get the action to dispatch.

=item getCurrentAction()

get current activate action.

=back

=cut

