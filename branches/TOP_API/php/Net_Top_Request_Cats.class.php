<?php
class Net_Top_Request_Cats extends Net_Top_Request_Base_Cats
{
    static function get ( $args = null ) {
         return new Net_Top_Request_Cats_Get($args);
    }

    static function propsGet ( $args = null ) {
         return new Net_Top_Request_Cats_PropsGet($args);
    }

    static function propvaluesGet ( $args = null ) {
         return new Net_Top_Request_Cats_PropvaluesGet($args);
    }

    static function spuGet ( $args = null ) {
         return new Net_Top_Request_Cats_SpuGet($args);
    }
}

class Net_Top_Request_Cats_Get extends Net_Top_Request_Base_Cats_Get 
{
}

class Net_Top_Request_Cats_PropsGet extends Net_Top_Request_Base_Cats_PropsGet 
{
}

class Net_Top_Request_Cats_PropvaluesGet extends Net_Top_Request_Base_Cats_PropvaluesGet 
{
}

class Net_Top_Request_Cats_SpuGet extends Net_Top_Request_Base_Cats_SpuGet 
{
}
