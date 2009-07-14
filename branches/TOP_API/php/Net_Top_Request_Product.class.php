<?php
class Net_Top_Request_Product extends Net_Top_Request_Base_Product
{
    static function get ( $args = null ) {
         return new Net_Top_Request_Product_Get($args);
    }

    static function search ( $args = null ) {
         return new Net_Top_Request_Product_Search($args);
    }
}

class Net_Top_Request_Product_Get extends Net_Top_Request_Base_Product_Get 
{
}

class Net_Top_Request_Product_Search extends Net_Top_Request_Base_Product_Search 
{
}
