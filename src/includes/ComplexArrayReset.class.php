<?php

class ComplexArrayReset extends WSArrays
{
    /**
     * Reset one array or all arrays.
     *
     * @param Parser $parser
     * @param string $array
     */
    public static function defineParser( Parser $parser, $array = '') {
        if(empty($array)) {
            WSArrays::$arrays = [];
        } else {
            unset(WSArrays::$arrays[$array]);
        }
    }
}