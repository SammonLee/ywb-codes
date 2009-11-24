use inc::Module::Install;

# Define metadata
name           'Farsail';
all_from       'lib/Farsail.pm';

requires 'AppConfig'       => '1.66';
requires 'Log::Log4perl'   => '1.14';
requires 'List::MoreUtils' => '0.22';
requires 'Return::Value'   => '1.666001',
requires 'version'         => '0.50';

test_requires  'Test::More'  => '0.42';

no_index       'directory'   => 't';

install_script 'scripts/farsail_do';

auto_install();

WriteAll;
