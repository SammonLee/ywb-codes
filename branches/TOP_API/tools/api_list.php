<?php
if ( $_REQUEST['pAction'] == 'catList' ) {
    include('api_list.txt');
}
elseif ( $_REQUEST['pAction'] == 'catProperty' ) {
    include('api_params.txt');
}

    