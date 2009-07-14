use Module::Build;

my $build = Module::Build->new(
    module_name => 'Net::Top',
    requires    =>
        {
            'Log::Log4perl'  => '',
            'Class::Accessor' => '',
            'XML::Simple' => '',
            'LWP::UserAgent' => '',
            'URI' => '',
        },
    create_makefile_pl => 'passthrough',
);

$build->create_build_script();
