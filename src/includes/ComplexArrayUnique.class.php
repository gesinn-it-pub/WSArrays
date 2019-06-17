<?php

class ComplexArrayUnique extends WSArrays
{
    public static function defineParser( Parser $parser, $name = '') {
        if(empty($name)) return GlobalFunctions::error("Name must not be omitted");

        // TODO: Create function to filter unique values in multidimensional array (array_unique() does not do this)
    }
}