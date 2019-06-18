<?php

class ComplexArrayReset extends WSArrays
{
    /**
     * Define all allowed parameters.
     *
     * @param Parser $parser
     * @param string $array
     */
    public static function defineParser( Parser $parser, $array = '') {
        self::arrayReset($array);
    }

    /**
     * Reset all or one array.
     *
     * @param string $array
     */
    private static function arrayReset($array = '') {
        if(empty($array)) {
            WSArrays::$arrays = [];
        } else {
            unset(WSArrays::$arrays[$array]);
        }
    }
}