<?php
class GdImage 
{
    private $im;

    function __construct($width, $height)
    {
        $this->im = imagecreate($width, $height);
    }

    function getColor($color)
    {
        static $allocColors = array();
        if ( isset($allocColors[$color]) ) // get color using color resource
            return $allocColors[$color];
        $color = GdColor::parseColor($color);
        if ( $color ) {
            $hexColor = $color->hexColor();
            if ( !isset($allocColors[$hexColor]) ) {
                $c = imagecolorallocate($this->im, $color->red(), $color->green(), $color->blue());
                $allocColors[$hexColor] = $c;
                $allocColors[$c] = $c;
            }
            return $allocColors[$hexColor];
        }
    }

    function save($file, $type='png')
    {
        $img_type = constant('IMG_' . strtoupper($type) );
        if ( $img_type && (imagetypes() & $img_type) ) {
            $func = 'image' . strtolower($type);
            return $func($this->im, $file);
        }
    }

    function getImage()
    {
        return $this->im;
    }

    function setBrush($brush)
    {
        if ( is_resource($brush) && get_resource_type($brush) == 'gd' )
            return imagesetbrush($this->im, $brush);
        elseif ( is_a($brush, 'GdImage') )
            return imagesetbrush($this->im, $brush->getImage());
        return false;
    }

    function __call($name, $args) 
    {
        $func = 'image' . strtolower($name);
        if ( function_exists($func) ) {
            array_unshift($args, $this->im);
            return call_user_func_array($func, $args);
        } else {
            throw new Exception("Method {$name} is not exists");
        }
    }
}
