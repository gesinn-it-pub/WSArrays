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
 * Class ComplexArrayReset
 *
 * Defines the parser function {{#complexarrayreset:}}, which allows users to reset all or one array.
 *
 * @extends WSArrays
 */
class ComplexArrayReset extends WSArrays {
    /**
     * Define all allowed parameters.
     *
     * @param Parser $parser
     * @param string $array
     */
    public static function defineParser( Parser $parser, $array = '' ) {
        GlobalFunctions::fetchSemanticArrays();

        ComplexArrayReset::arrayReset( $array );
    }

    /**
     * Reset all or one array.
     *
     * @param string $array
     */
    private static function arrayReset( $array = '' ) {
        if ( empty( $array ) ) {
            WSArrays::$arrays = [];
        } else {
            if ( isset( WSArrays::$arrays[ $array ] ) ) {
                unset( WSArrays::$arrays[ $array ] );
            }
        }
    }
}