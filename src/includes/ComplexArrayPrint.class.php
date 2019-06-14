<?php

class ComplexArrayPrint extends WSArrays
{
    protected static $array = [];

    private static $name = null;
    private static $map = null;
    private static $subject = "@@";
    private static $indent_char = "*";

    public static function defineParser( Parser $parser, $name = '', $options = '', $map = '', $subject = '') {
        if(empty($name)) return GlobalFunctions::error("You must provide a name");

        if(!self::$array = GlobalFunctions::getArrayFromArrayName($name)) return GlobalFunctions::error("This array has not been defined");

        self::$name = $name;
        self::$map = $map;

        if($subject) self::$subject = $subject;

        if(!empty($options)) {
            GlobalFunctions::serializeOptions($options);

            $result = self::applyOptions($options);
        } else {
            $result = self::createList();
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
                return self::createList($options);
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

        $wson = str_replace("{", "((", $json);
        return str_replace("}", "))", $wson);
    }

    private static function createList($type = "unordered") {
        if($type == "ordered") self::$indent_char = "#";
        $indent_char = self::$indent_char;

        $result = null;

        foreach(self::$array as $key => $value) {
            if(!is_array($value)) {
                if(!is_numeric($key)) {
                    $result .= "$indent_char $key: $value\n";
                } else {
                    $result .= "$indent_char $value\n";
                }
            } else {
                $result .= "$indent_char ".strval($key)."\n";

                self::addArrayToUnorderedList($value, $result);
            }
        }

        return $result;
    }

    private static function addArrayToUnorderedList($array, &$result, $depth = 0) {
        $depth++;

        $indent_char = self::$indent_char;

        foreach($array as $key => $value) {
            $indent = str_repeat("$indent_char", $depth + 1);

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