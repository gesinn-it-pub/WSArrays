<?php

class ComplexArrayPushValue extends WSArrays
{
    /**
     * Define all allowed parameters.
     *
     * @param Parser $parser
     * @param string $array
     * @param string $value
     * @return array|bool|null
     */
    public static function defineParser( Parser $parser, $array = '', $value = '') {
        if(empty($array) || empty($value)) return GlobalFunctions::error("Array or value omitted");

        return self::pushValueToArray($array, $value);
    }

    private static function pushValueToArray($array, $value) {
        $base_array = strtok($array, "[");
        if(!$wsarray = WSArrays::$arrays[$base_array]) return GlobalFunctions::error("This array has not been defined");

        if(!strpos($array, "[")) {
            GlobalFunctions::parseWSON($value);

            if(GlobalFunctions::isValidJSON($value)) {
                $value = json_decode($value, true);
            }

            array_push($wsarray, $value);

            WSArrays::$arrays[$base_array] = $wsarray;

            return null;
        }

        $valid = preg_match_all("/(?<=\[).+?(?=\])/", $array, $matches);
        if($valid === 0) return GlobalFunctions::error("This name is invalid");

        $result = self::add($matches[0], $wsarray, $value);

        if($result !== true) return $result;

        WSArrays::$arrays[$base_array] = $wsarray;

        return null;
    }

    private static function add($path, &$array = array(), $value = null) {
        GlobalFunctions::parseWSON($value);

        if(GlobalFunctions::isValidJSON($value)) {
            $value = json_decode($value, true);
        }

        $temp =& $array;

        $depth = count($path);

        $current_depth = 1;
        foreach($path as $key) {
            $current_depth++;

            if(!array_key_exists($key, $temp)) return GlobalFunctions::error("You cannot push to a non-existent subarray");

            if($current_depth !== $depth) {
                $temp =& $temp[$key];
            }
        }

        array_push($temp, $value);

        return true;
    }
}