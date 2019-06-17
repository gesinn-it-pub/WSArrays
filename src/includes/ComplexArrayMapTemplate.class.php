<?php

class ComplexArrayMapTemplate extends WSArrays
{
    public static function defineParser( Parser $parser, $name = '', $template = '') {
        if(empty($name)) return GlobalFunctions::error("Name should not be omitted");
        if(empty($template)) return GlobalFunctions::error("Template should not be omitted");

        $base_array = strtok($name, "[");
        if(!$array = WSArrays::$arrays[$base_array]) return GlobalFunctions::error("This array has not been defined");

        if(strpos($name, "[") && strpos($name, "]")) {
            if(!$array = GlobalFunctions::getArrayFromArrayName($name)) return GlobalFunctions::error("This array has not been defined");
        }

        $return = null;

        if(GlobalFunctions::containsArray($array)) {
            foreach($array as $value) {
                self::map($value, $return, $template);
            }
        } else {
            self::map($array, $return, $template);
        }

        return array($return, "noparse" => false);
    }

    private static function map($value, &$return, $template) {
        $t = "{{".$template;
        foreach($value as $key => $subvalue) {
            if(is_array($subvalue)) {
                $json = json_encode($subvalue);
                GlobalFunctions::parseJSON($json);

                $subvalue = $json;
            }

            $t .= "|$key=$subvalue";
        }

        $return .= $t."}}";
    }
}