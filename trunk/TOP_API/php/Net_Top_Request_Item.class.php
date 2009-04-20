<?php
class Net_Top_Request_Item extends Net_Top_Request_Base_Item
{
    static function get ( $args = null ) {
         return new Net_Top_Request_Item_Get($args);
    }

    static function itemsGet ( $args = null ) {
         return new Net_Top_Request_Item_ItemsGet($args);
    }
}

class Net_Top_Request_Item_Get extends Net_Top_Request_Base_Item_Get 
{
}

class Net_Top_Request_Item_ItemsGet extends Net_Top_Request_Base_Item_ItemsGet 
{
}
