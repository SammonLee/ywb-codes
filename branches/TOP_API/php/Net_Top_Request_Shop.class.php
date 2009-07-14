<?php
class Net_Top_Request_Shop extends Net_Top_Request_Base_Shop
{
    static function get ( $args = null ) {
         return new Net_Top_Request_Shop_Get($args);
    }

    static function showCaseRemainCount ( $args = null ) {
         return new Net_Top_Request_Shop_ShowCaseRemainCount($args);
    }

    static function update ( $args = null ) {
         return new Net_Top_Request_Shop_Update($args);
    }
}

class Net_Top_Request_Shop_Get extends Net_Top_Request_Base_Shop_Get 
{
}

class Net_Top_Request_Shop_ShowCaseRemainCount extends Net_Top_Request_Base_Shop_ShowCaseRemainCount 
{
}

class Net_Top_Request_Shop_Update extends Net_Top_Request_Base_Shop_Update 
{
}
