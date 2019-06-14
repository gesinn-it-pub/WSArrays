<?php

class ComplexArrayDefine extends WSArrays
{
    /**
     * @param Parser $parser
     * @param string $name The name of the array that is going to be defined
     * @param string $json The array, encoded in valid JSON
     *
     * @return array|bool
     */
    public static function defineParser( Parser $parser, $name = '', $wson = '') {
        if(empty($name)) return GlobalFunctions::error("You must provide a name");
        if(empty($wson)) return GlobalFunctions::error("You must provide an array");

        GlobalFunctions::parseWSON($wson);

        if(!GlobalFunctions::isValidJSON($wson)) return GlobalFunctions::error("You must provide the array in valid WSON (see docs)");

        $array = json_decode($wson, true);

        WSArrays::$arrays[$name] = $array;

        return null;
    }
}