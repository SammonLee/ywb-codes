package TopConfig;

use Net::Top;
use Readonly;

Readonly our $TOP_APPKEY => '';
Readonly our $TOP_SECRET_KEY => '';

sub getClient{
    return new Net::Top({
        top_appkey => $TOP_APPKEY,
        top_secret_key => $TOP_SECRET_KEY
    });
}

1;
