<?php

class ComplexArraySort extends WSArrays
{
    private static $key;
    private static $name;
    private static $array;

    public static function defineParser( Parser $parser, $name = '', $options = '', $key = '') {
        if(empty($name)) return GlobalFunctions::error("Name must not be omitted");
        if(!self::$array = WSArrays::$arrays[$name]) return GlobalFunctions::error("This array does not exist");

        self::$name = $name;
        if(!empty($key)) self::$key = $key;

        if(empty($options)) {
            $result = self::sortArray("sort");
        } else {
            $result = self::sortArray($options);
        }

        if($result === true) {
            return null;
        } else {
            return GlobalFunctions::error($result);
        }
    }

    private static function sortArray($algo) {
        switch($algo) {
            case 'multisort':
                $array = self::multisort();
                break;
            case 'asort':
                $array = self::asort();
                break;
            case 'arsort':
                $array = self::arsort();
                break;
            case 'krsort':
                $array = self::krsort();
                break;
            case 'natcasesort':
                $array = self::natcasesort();
                break;
            case 'natsort':
                $array = self::natsort();
                break;
            case 'rsort':
                $array = self::rsort();
                break;
            case 'shuffle':
                $array = self::shuffle();
                break;
            case 'keysort':
                $array = self::keysort();
                break;
            case 'sort':
            default:
                $array = self::sort();
                break;
        }

        if($array === true) {
            self::saveArray();
            return true;
        } else {
            return $array;
        }
    }

    private static function multisort() {
        if(!array_multisort(self::$array)) return "This array cannot be sorted using multisort";

        return true;
    }

    private static function asort() {
        if(!asort(self::$array)) return "This array cannot be sorted using asort";

        return true;
    }

    private static function arsort() {
        if(!arsort(self::$array)) return "This array cannot be sorted using arsort";

        return true;
    }

    private static function krsort() {
        if(!krsort(self::$array)) return "This array cannot be sorted using krsort";

        return true;
    }

    private static function natcasesort() {
        if(!natcasesort(self::$array)) return "This array cannot be sorted using natcasesort";

        return true;
    }

    private static function natsort() {
        if(!natsort(self::$array)) return "This array cannot be sorted using natsort";

        return true;
    }

    private static function rsort() {
        if(!rsort(self::$array)) return "This array cannot be sorted using rsort";

        return true;
    }

    private static function shuffle() {
        if(!shuffle(self::$array)) return "This array cannot be sorted using shuffle";

        return true;
    }

    private static function sort() {
        if(!sort(self::$array)) return "This array cannot be sorted using sort";

        return true;
    }

    private static function keysort() {
        if(!self::$key) return "Key must not be omitted when using keysort";
        if(GlobalFunctions::arrayMaxDepth(self::$array) !== 1) return "This array cannot be sorted using keysort (must be 2-dimensional)";

        foreach(self::$array as $element) {
            if(!$element[self::$key]) return "This is not a uniform 2-dimensional array, or key does not exist";
        }

        self::ksort(self::$array, self::$key);

        return true;
    }

    private static function ksort(&$array, $key) {
        $sorter = array();
        $ret = array();

        reset($array);

        foreach($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }

        asort($sorter);

        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }

        $array = $ret;
    }

    private static function saveArray() {
        WSArrays::$arrays[self::$name] = self::$array;
    }
}