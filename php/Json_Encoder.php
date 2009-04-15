<?php
/**
 * brought from Zend
 */
class Json_Encoder
{
    protected $_cycleCheck;
    protected $_options = array();
    protected $_visited = array();
    protected function __construct($cycleCheck = false, $options = array())
    {
        $this->_cycleCheck = $cycleCheck;
        $this->_options = $options;
    }

    public static function encode($value, $cycleCheck = false, $options = array())
    {
        if ( isset($options['indent']) && is_integer($options['indent']) ) {
            $options['indent'] = self::makeString(' ', $options['indent']);
        }
        $encoder = new self(($cycleCheck) ? true : false, $options);
        return $encoder->_encodeValue($value, 0);
    }

    protected function _encodeValue(&$value, $level)
    {
        if (is_object($value)) {
            return $this->_encodeObject($value, $level);
        } else if (is_array($value)) {
            return $this->_encodeArray($value, $level);
        }
        return $this->_encodeDatum($value);
    }

    protected function _encodeObject(&$value, $level)
    {
        if ($this->_cycleCheck) {
            if ($this->_wasVisited($value)) {
                if (isset($this->_options['silenceCyclicalExceptions'])
                    && $this->_options['silenceCyclicalExceptions']===true) {
                    return '"* RECURSION (' . get_class($value) . ') *"';
                } else {
                    throw new Exception(
                        'Cycles not supported in JSON encoding, cycle introduced by '
                        . 'class "' . get_class($value) . '"'
                        );
                }
            }
            $this->_visited[] = $value;
        }

        if ( isset($this->_options['indent']) ) {
            $indent0 = self::makeString($this->_options['indent'], $level);
            $indent1 = $indent0 . $this->_options['indent'];
        }
        $props = '';
        foreach (get_object_vars($value) as $name => $propValue) {
            if (isset($propValue)) {
                $props .= ',';
                if ( isset($this->_options['indent']) ) {
                    $props .= "\n" . $indent1;
                }
                $props .= $this->_encodeValue($name, $level+1)
                    . ':'
                    . $this->_encodeValue($propValue, $level+1);
            }
        }
        
        if ( isset($this->_options['indent']) ) {
            return  '{' . "\n"
                . $indent1 . '"__className":"' . get_class($value) . '"'
                . $props . "\n"
                . $indent0 + '}';
        } else {
            return '{"__className":"' . get_class($value) . '"' . $props . '}';
        }
    }

    protected function _wasVisited(&$value)
    {
        if (in_array($value, $this->_visited, true)) {
            return true;
        }

        return false;
    }
    
    protected function _encodeArray(&$array, $level)
    {
        $tmpArray = array();
        if ( isset($this->_options['indent']) ) {
            $indent0 = self::makeString($this->_options['indent'], $level);
            $indent1 = $indent0 . $this->_options['indent'];
        }
        // Check for associative array
        if (!empty($array) && (array_keys($array) !== range(0, count($array) - 1))) {
            // Associative array
            $result = '{';
            if ( isset($this->_options['indent']) ) {
                $result .= "\n" . $indent1;
            }
            foreach ($array as $key => $value) {
                $key = (string) $key;
                $tmpArray[] = $this->_encodeString($key)
                            . ':'
                    . $this->_encodeValue($value, $level+1);
            }
            $result .= implode( (isset($this->_options['indent']) ? ",\n".$indent1 : ','), $tmpArray);
            if ( isset($this->_options['indent']) )
                $result .= "\n" . $indent0;
            $result .= '}';
        } else {
            // Indexed array
            $result = '[';
            $length = count($array);
            if ( isset($this->_options['indent']) && $length > 0 ) {
                $result .= "\n" . $indent1;
            }
            for ($i = 0; $i < $length; $i++) {
                $tmpArray[] = $this->_encodeValue($array[$i], $level+1);
            }
            $result .= implode((isset($this->_options['indent']) ? ",\n".$indent1 : ','), $tmpArray);
            if ( isset($this->_options['indent']) && $length > 0 )
                $result .= "\n" . $indent0;
            $result .= ']';
        }
        return $result;
    }

    protected function _encodeDatum(&$value)
    {
        $result = 'null';

        if (is_int($value) || is_float($value)) {
            $result = (string)$value;
        } elseif (is_string($value)) {
            $result = $this->_encodeString($value);
        } elseif (is_bool($value)) {
            $result = $value ? 'true' : 'false';
        }

        return $result;
    }

    protected function _encodeString(&$string)
    {
        // Escape these characters with a backslash:
        // " \ / \n \r \t \b \f
        $search  = array('\\', "\n", "\t", "\r", "\b", "\f", '"');
        $replace = array('\\\\', '\\n', '\\t', '\\r', '\\b', '\\f', '\"');
        $string  = str_replace($search, $replace, $string);

        // Escape certain ASCII characters:
        // 0x08 => \b
        // 0x0c => \f
        $string = str_replace(array(chr(0x08), chr(0x0C)), array('\b', '\f'), $string);

        return '"' . $string . '"';
    }

    public static function makeString($str, $count)
    {
        if ( $count > 0 ) {
            return implode('', array_fill(0, $count, $str));
        } else {
            return '';
        }
    }
}

function json_encode_pretty($val, $indent=4)
{
    return Json_Encoder::encode($val, false, array('indent' => $indent));
}
