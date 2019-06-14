<?php

class ComplexArrayReset extends WSArrays
{
    public static function defineParser( Parser $parser, $array = '') {
        if(empty($array)) {
            WSArrays::$arrays = [];
        } else {
            unset(WSArrays::$arrays[$array]);
        }
    }
}