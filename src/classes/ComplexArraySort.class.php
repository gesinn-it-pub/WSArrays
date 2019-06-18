<?php

/**
 * Class ComplexArraySort
 *
 * Defines the parser function {{#complexarraysort:}}, which allows users to sort arrays.
 *
 * @extends WSArrays
 */
class ComplexArraySort extends WSArrays
{
    private static $key;
    private static $name;
    private static $array;

    /**
     * Define all allowed parameters.
     *
     * @param Parser $parser
     * @param string $name
     * @param string $options
     * @param string $key
     * @return array|null
     */
    public static function defineParser( Parser $parser, $name = '', $options = '', $key = '') {
        $ca_omitted = wfMessage('ca-omitted');
        if(empty($name)) return GlobalFunctions::error($ca_omitted);

        return self::arraySort($name, $options, $key);
    }

    /**
     * @param $name
     * @param string $options
     * @param string $key
     * @return array|null
     */
    private static function arraySort($name, $options = '', $key = '') {
        $ca_undefined_array = wfMessage('ca-undefined-array');
        if(!isset(WSArrays::$arrays[$name])) return GlobalFunctions::error($ca_undefined_array);
        self::$array = WSArrays::$arrays[$name];

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

    /**
     * @param $algo
     * @return bool|string
     */
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

    /**
     * Sort array using multisort
     *
     * @return bool|string
     */
    private static function multisort() {
        $ca_sort_broken = wfMessage('ca-sort-broken', 'multisort');
        if(!array_multisort(self::$array)) return $ca_sort_broken;

        return true;
    }

    /**
     * Sort array using asort
     *
     * @return bool|string
     */
    private static function asort() {
        $ca_sort_broken = wfMessage('ca-sort-broken', 'asort');
        if(!asort(self::$array)) return $ca_sort_broken;

        return true;
    }

    /**
     * Sort array using arsort
     *
     * @return bool|string
     */
    private static function arsort() {
        $ca_sort_broken = wfMessage('ca-sort-broken', 'arsort');
        if(!arsort(self::$array)) return $ca_sort_broken;

        return true;
    }

    /**
     * Sort array using krsort
     *
     * @return bool|string
     */
    private static function krsort() {
        $ca_sort_broken = wfMessage('ca-sort-broken', 'krsort');
        if(!krsort(self::$array)) return $ca_sort_broken;

        return true;
    }

    /**
     * Sort array using natcasesort
     *
     * @return bool|string
     */
    private static function natcasesort() {
        $ca_sort_broken = wfMessage('ca-sort-broken', 'natcasesort');
        if(!natcasesort(self::$array)) return $ca_sort_broken;

        return true;
    }

    /**
     * Sort array using natsort
     *
     * @return bool|string
     */
    private static function natsort() {
        $ca_sort_broken = wfMessage('ca-sort-broken', 'natsort');
        if(!natsort(self::$array)) return $ca_sort_broken;

        return true;
    }

    /**
     * Sort array using rsort
     *
     * @return bool|string
     */
    private static function rsort() {
        $ca_sort_broken = wfMessage('ca-sort-broken', 'rsort');
        if(!rsort(self::$array)) return $ca_sort_broken;

        return true;
    }

    /**
     * Sort array using shuffle
     *
     * @return bool|string
     */
    private static function shuffle() {
        $ca_sort_broken = wfMessage('ca-sort-broken', 'shuffle');
        if(!shuffle(self::$array)) return $ca_sort_broken;

        return true;
    }

    /**
     * Sort array using sort
     *
     * @return bool|string
     */
    private static function sort() {
        $ca_sort_broken = wfMessage('ca-sort-broken', 'sort');
        if(!sort(self::$array)) return $ca_sort_broken;

        return true;
    }

    /**
     * Sort array using keysort
     *
     * @return bool|string
     */
    private static function keysort() {
        $ca_sort_missing_key = wfMessage('ca-sort-missing-key');
        if(!self::$key) return $ca_sort_missing_key;

        $ca_sort_array_too_deep = wfMessage('ca-sort-array-too-deep');
        if(GlobalFunctions::arrayMaxDepth(self::$array) !== 1) return $ca_sort_array_too_deep;

        self::ksort(self::$array, self::$key);

        $i = 0;
        $temp = [];
        foreach(self::$array as $key => $item) {
            $temp[$i] = $item;
            $i++;
        }

        self::$array = $temp;

        return true;
    }

    /**
     * User-defined sorting function which sorts based on key.
     *
     * @param $array
     * @param $key
     * @return bool|string
     */
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

    /**
     * Save array.
     */
    private static function saveArray() {
        WSArrays::$arrays[self::$name] = self::$array;
    }
}