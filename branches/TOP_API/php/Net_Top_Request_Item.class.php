<?php
class Net_Top_Request_Item 
{
    const API_TYPE = 'Item';
    
    static function __call($name, $args)
    {
        $ua = Net_Top::factory();
        $req = Net_Top_Request::factory(
            self::API_TYPE . ucfirst($name),
            $args
            );
        return $ua->request($req);
    }
}
