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
 * Class ComplexArrayDefine
 *
 * Defines the parser function {{#complexarraydefine:}}, which allows users to define a new array.
 *
 * @extends WSArrays
 */
class ComplexArrayDefine extends WSArrays {
    /**
     * Define all allowed parameters.
     *
     * @param Parser $parser
     * @param string $name The name of the array that is going to be defined
     * @param string $wson The array, encoded in valid JSON
     *
     * @throws Exception
     *
     * @return null
     */
    public static function defineParser( Parser $parser, $name = '', $wson = '' ) {
        GlobalFunctions::fetchSemanticArrays();

        if ( empty( $name ) ) {
            $ca_omitted = wfMessage( 'ca-omitted', 'Name' );

            return GlobalFunctions::error( $ca_omitted );
        }

        if ( !GlobalFunctions::isValidArrayName( $name ) ) {
            $ca_invalid_name = wfMessage( 'ca-invalid-name' );

            return GlobalFunctions::error( $ca_invalid_name );
        }

        // Define an empty array
        if ( empty($wson) ) {
            WSArrays::$arrays[ $name ] = new SafeComplexArray( array() );

            return null;
        }

        return ComplexArrayDefine::arrayDefine( $name, $wson );
    }

    /**
     * Define array and store it in WSArrays::$arrays as a SafeComplexArray object.
     *
     * @param $name
     * @param $wson
     * @return array|null
     * @throws Exception
     */
    private static function arrayDefine( $name, $wson ) {
        GlobalFunctions::WSONtoJSON( $wson );

        if ( !GlobalFunctions::isValidJSON( $wson ) ) {
            $ca_invalid_wson = wfMessage( 'ca-invalid-wson' );

            return GlobalFunctions::error( $ca_invalid_wson );
        }

        if ( GlobalFunctions::definedArrayLimitReached() ) {
            $ca_max_defined_arrays_reached = wfMessage( 'ca-max-defined-arrays-reached', WSArrays::$options[ 'max_defined_arrays' ], $name );

            return GlobalFunctions::error( $ca_max_defined_arrays_reached );
        }

        $array = json_decode( $wson, true );

        WSArrays::$arrays[ $name ] = new SafeComplexArray( $array );

        return null;
    }
}