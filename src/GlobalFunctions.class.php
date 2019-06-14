<?php

/**
 * Class GlobalFunctions
 *
 * Grandfather class. These functions are available in all other classes.
 */
class GlobalFunctions
{
    public static function error($message) {
        $params = func_get_args();
        array_shift( $params );

        $msgHtml = Html::rawElement(
            'span',
            array( 'class' => 'error' ),
            wfMessage( $message, $params )->toString()
        );

        return array( $msgHtml, 'noparse' => true, 'isHTML' => false );
    }

    public static function isValidJSON($json) {
        json_decode($json);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function parseWSON(&$json) {
        $json = str_replace("((", "{", $json);
        $json = str_replace("))", "}", $json);
    }

    public static function serializeOptions(&$options) {
        $options = explode(",", $options);
    }

    public static function containsArray($array) {
        if(!is_array($array)) return false;

        foreach($array as $value) {
            if(is_array($value)) return true;
        }

        return false;
    }

    public static function getArrayFromArrayName($name) {
        if(!strpos($name, "[")) {
            if(WSArrays::$arrays[$name]) return WSArrays::$arrays[$name];
        } else {
            $base_array = strtok($name, "[");
            if(!$array = WSArrays::$arrays[$base_array]) return false;

            $valid = preg_match_all("/(?<=\[).+?(?=\])/", $name, $matches);
            if($valid === 0) return false;

            foreach($matches[0] as $match) {
                if(ctype_digit($match)) $match = intval($match);

                $current_array = $array;
                foreach($array as $key => $value) {
                    if($key === $match) {
                        $array = $value;
                        break;
                    }
                }

                if($current_array === $array) return false;
            }

            return $array;
        }

        return false;
    }
}