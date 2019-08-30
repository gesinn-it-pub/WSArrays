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
 * Class ComplexArrayPushArray
 *
 * Defines the parser function {{#complexarraypusharray:}}, which allows users to push one or more arrays to the end of another array, creating a new array.
 *
 * @extends WSArrays
 */
class ComplexArrayPushArray extends ResultPrinter {
    public function getName() {
        return 'complexarraypusharray';
    }

    public function getAliases() {
        return [
            'capusharray'
        ];
    }

    public function getType() {
        return 'normal';
    }

    /**
     * @var string
     */
    private static $new_array = '';

    /**
     * Define parameters and initialize parser.
     *
     * @param Parser $parser
     * @return array|null
     *
     * @throws Exception
     */
    public static function getResult( Parser $parser ) {
        GlobalFunctions::fetchSemanticArrays();

        return ComplexArrayPushArray::arrayPush( func_get_args() );
    }

    /**
     * @param $args
     * @return array|null
     *
     * @throws Exception
     */
    private static function arrayPush( $args ) {
        ComplexArrayPushArray::parseFunctionArguments( $args );

        if ( !GlobalFunctions::isValidArrayName( ComplexArrayPushArray::$new_array ) ) {
            $ca_invalid_name = wfMessage( 'ca-invalid-name' );

            return GlobalFunctions::error( $ca_invalid_name );
        }

        if( count( $args ) < 2 ) {
            $ca_too_little_arrays = wfMessage( 'ca-too-little-arrays' );

            return GlobalFunctions::error( $ca_too_little_arrays );
        }

        $arrays = ComplexArrayPushArray::iterate( $args );

        WSArrays::$arrays[ComplexArrayPushArray::$new_array] = new SafeComplexArray( $arrays );

        return null;
    }

    /**
     * @param array $array
     * @return array|bool
     *
     * @throws Exception
     */
    private static function iterate( $array ) {
        $arrays = [];
        foreach ( $array as $array_name ) {
            if ( !GlobalFunctions::arrayExists( $array_name ) ) {
                continue;
            }

            $push_array = GlobalFunctions::getArrayFromSafeComplexArray( WSArrays::$arrays[ $array_name ] );

            array_push( $arrays, $push_array );
        }

        return $arrays;
    }

    /**
     * @param $args
     */
    private static function parseFunctionArguments( &$args ) {
        ComplexArrayPushArray::removeFirstItemFromArray( $args );
        ComplexArrayPushArray::getFirstItemFromArray( $args );
        ComplexArrayPushArray::removeFirstItemFromArray( $args );
    }

    /**
     * @param $array
     */
    private static function removeFirstItemFromArray( &$array ) {
        array_shift( $array );
    }

    /**
     * @param $array
     */
    private static function getFirstItemFromArray( &$array ) {
        ComplexArrayPushArray::$new_array = reset( $array );
    }
}