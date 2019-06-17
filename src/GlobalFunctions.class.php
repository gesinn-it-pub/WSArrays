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

    public static function parseWSON(&$wson) {
        $wson = preg_replace("/(?!\B\"[^\"]*)\(\((?![^\"]*\"\B)/i", "{", $wson);
        $wson = preg_replace("/(?!\B\"[^\"]*)\)\)(?![^\"]*\"\B)/i", "}", $wson);
    }

    public static function parseJSON(&$json) {
        $json = preg_replace("/(?!\B\"[^\"]*){(?![^\"]*\"\B)/i", "((", $json);
        $json = preg_replace("/(?!\B\"[^\"]*)}(?![^\"]*\"\B)/i", "))", $json);
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

    /**
     * @param $name
     * @return bool|array
     */
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

    public static function arrayMaxDepth($array, $depth = 0) {
        $max_sub_depth = 0;
        foreach (array_filter($array, 'is_array') as $subarray) {
            $max_sub_depth = max(
                $max_sub_depth,
                self::arrayMaxDepth($subarray, $depth + 1)
            );
        }

        return $max_sub_depth + $depth;
    }
}