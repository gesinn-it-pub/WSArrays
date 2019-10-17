<?php

/**
 * WSArrays - Associative and multidimensional arrays for MediaWiki.
 * Copyright (C) 2019 Marijn van Wezel
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

/**
 * Class ComplexArrayAddValue
 *
 * Defines the parser function {{#complexarrayaddvalue:}}, which allows users to add values to (sub)arrays.
 *
 * @extends WSArrays
 */
class ComplexArrayAddValue extends ResultPrinter {
    public function getName() {
        return 'complexarrayaddvalue';
    }

    public function getAliases() {
        return [
            'caaddvalue',
            'caadd',
            'caaddv'
        ];
    }

    public function getType() {
        return 'normal';
    }

    /**
     * Define parameters and initialize parser.
     *
     * @param Parser $parser
     * @param string $name
     * @param string $value
     * @return array|null
     *
     * @throws Exception
     */
    public static function getResult( Parser $parser, $name = '', $value = '' ) {
        GlobalFunctions::fetchSemanticArrays();

        if ( empty( $name ) ) {
            $ca_omitted = wfMessage( 'ca-omitted', 'Name' );

            return GlobalFunctions::error( $ca_omitted );
        }

        if ( empty( $value ) ) {
            $ca_omitted = wfMessage( 'ca-omitted', 'Value' );

            return GlobalFunctions::error( $ca_omitted );
        }

        if ( !strpos( $name, "[" ) ||
             !strpos( $name, "]" ) ) {
            $ca_subarray_not_provided = wfMessage( 'ca-subarray-not-provided' );

            return GlobalFunctions::error( $ca_subarray_not_provided );
        }

        return ComplexArrayAddValue::arrayAddValue( $name, $value );
    }

    /**
     * This function first calculates the name of the base array, then fetches that array and adds a value to the array.
     * The array is then saved again under the same name with the value added.
     *
     * @param $array
     * @param $value
     * @return array|null
     *
     * @throws Exception
     */
    private static function arrayAddValue( $array, $value ) {
        $base_array = GlobalFunctions::calculateBaseArray( $array );

        /*
         * The array has not been found, return.
         */
        if ( !GlobalFunctions::arrayExists( $base_array ) ) {
            return null;
        }

        /*
         * This function creates an array $matches[0] of all the subarrays in the name.
         */
        if ( preg_match_all( "/(?<=\[).+?(?=\])/", $array, $matches ) === 0 ) {
            $ca_invalid_name = wfMessage( 'ca-invalid-name' );

            return GlobalFunctions::error( $ca_invalid_name );
        }

        global $wfEscapeEntitiesInArrays;

        if ( $wfEscapeEntitiesInArrays === true ) {
            $wsarray = GlobalFunctions::getArrayFromSafeComplexArray( WSArrays::$arrays[ $base_array ] );
        } else {
            $wsarray = GlobalFunctions::getUnsafeArrayFromSafeComplexArray( WSArrays::$arrays[ $base_array ] );
        }


        ComplexArrayAddValue::set( $matches[ 0 ], $wsarray, $value );

        WSArrays::$arrays[ $base_array ] = new SafeComplexArray( $wsarray );

        return null;
    }

    /**
     * @param $path
     * @param array $array
     * @param null $value
     */
    private static function set( $path, &$array = array(), $value = null ) {
        GlobalFunctions::WSONtoJSON( $value );

        if ( GlobalFunctions::isValidJSON( $value ) ) {
            $value = json_decode( $value, true );
        }

        $temp =& $array;

        foreach( $path as $key ) {
            $temp =& $temp[ $key ];
        }

        $temp = $value;
    }
}