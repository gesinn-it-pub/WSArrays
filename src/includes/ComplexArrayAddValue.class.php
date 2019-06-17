<?php

class ComplexArrayAddValue extends WSArrays
{
    public static function defineParser( Parser $parser, $array = '', $value = '') {
        if(empty($array) || empty($value)) return GlobalFunctions::error("Array or value omitted");
        if(!strpos($array, "[") || !strpos($array, "]")) return GlobalFunctions::error("You must provide a subarray");

        return self::addValueToArray($array, $value);
    }

    private static function addValueToArray($array, $value) {
        $base_array = strtok($array, "[");
        if(!$wsarray = WSArrays::$arrays[$base_array]) return GlobalFunctions::error("This array has not been defined");

        $valid = preg_match_all("/(?<=\[).+?(?=\])/", $array, $matches);
        if($valid === 0) return GlobalFunctions::error("This name is invalid");

        self::set($matches[0], $wsarray, $value);

        WSArrays::$arrays[$base_array] = $wsarray;

        return null;
    }

    private static function set($path, &$array = array(), $value = null) {
        GlobalFunctions::parseWSON($value);

        if(GlobalFunctions::isValidJSON($value)) {
            $value = json_decode($value, true);
        }

        $temp =& $array;

        foreach($path as $key) {
            $temp =& $temp[$key];
        }

        $temp = $value;
    }
}