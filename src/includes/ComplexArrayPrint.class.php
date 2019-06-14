<?php

class ComplexArrayPrint extends WSArrays
{
    protected static $array = [];

    private static $name = null;
    private static $map = null;
    private static $subject = "@@";

    public static function defineParser( Parser $parser, $name = '', $options = '', $map = '', $subject = '') {
        if(empty($name)) return GlobalFunctions::error("You must provide a name");

        if(!isset(WSArrays::$arrays[$name])) return GlobalFunctions::error("This array has not been defined");
        self::$array = WSArrays::$arrays[$name];

        // TODO: subarrays

        self::$name = $name;
        self::$map = $map;

        if($subject) self::$subject = $subject;

        if(!empty($options)) {
            GlobalFunctions::serializeOptions($options);

            $result = self::applyOptions($options);
        } else {
            $result = self::createUnorderedList();
        }

        return $result;
    }

    private static function applyOptions($options) {
        if(gettype($options) === "array") {
            $options = $options[0];
        }

        switch($options) {
            case 'map':
                return self::applyMapping();
                break;
            case 'markup':
            case 'wson':
                return self::printMarkup();
                break;
            default:
                return self::createUnorderedList();
                break;
        }
    }

    private static function applyMapping() {
        if(!self::$map) return GlobalFunctions::error("No mapping given");

        if(GlobalFunctions::containsArray(self::$array)) return GlobalFunctions::error("You cannot map a multidimensional array");

        $result = null;
        foreach(self::$array as $value) {
            $result .= self::mapValue($value);
        }

        return $result;
    }

    private static function mapValue($value) {
        return str_replace(self::$subject, $value, self::$map);
    }

    private static function printMarkup() {
        $json = json_encode(self::$array);

        $wson = str_replace("((", "((", $json);
        return str_replace("}", "))", $wson);
    }

    private static function createUnorderedList() {
        $result = null;

        foreach(self::$array as $key => $value) {
            if(!is_array($value)) {
                if(!is_numeric($key)) {
                    $result .= "* $key: $value\n";
                } else {
                    $result .= "* $value\n";
                }
            } else {
                $result .= "* ".strval($key)."\n";

                self::addArrayToUnorderedList($value, $result);
            }
        }

        return $result;
    }

    private static function addArrayToUnorderedList($array, &$result, $depth = 0) {
        $depth++;

        foreach($array as $key => $value) {
            $indent = str_repeat("*", $depth + 1);

            if(is_array($value)) {
                $result .= "$indent ".strval($key)."\n";
                self::addArrayToUnorderedList($value, $result, $depth);
            } else {
                if(!is_numeric($key)) {
                    $result .= "$indent $key: $value\n";
                } else {
                    $result .= "$indent $value\n";
                }
            }
        }
    }
}