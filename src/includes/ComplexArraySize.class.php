<?php

class ComplexArraySize extends WSArrays
{
    public static function defineParser( Parser $parser, $name = '') {
        if(!$array = WSArrays::$arrays[$name]) return GlobalFunctions::error("This array has not been defined");

        $count = count($array, COUNT_RECURSIVE);

        return $count;
    }
}