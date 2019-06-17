<?php

class ComplexArraySize extends WSArrays
{
    public static function defineParser( Parser $parser, $name = '', $options = '') {
        $base_array = strtok($name, "[");
        if(!$array = WSArrays::$arrays[$base_array]) return GlobalFunctions::error("This array has not been defined");

        if(!strpos($name, "[") && empty($options)) {
            $count = count($array, COUNT_RECURSIVE);

            return $count;
        }

        if(!strpos($name, "[") && $options === "top") {
            $count = count($array);

            return $count;
        }

        if(!is_array($array = GlobalFunctions::getArrayFromArrayName($name))) return GlobalFunctions::error("This array has not been defined");

        if($options === "top") return count($array);

        return count($array, COUNT_RECURSIVE);
    }
}