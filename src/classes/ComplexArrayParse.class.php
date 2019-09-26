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
 * Class ComplexArrayParse
 *
 * Defines the parser function {{#complexarrayparse:}}, which allows users to forcefully parse a string.
 *
 * @extends WSArrays
 */
class ComplexArrayParse extends ResultPrinter {
    public function getName() {
        return 'complexarrayparse';
    }

    public function getAliases() {
        return [
            'caparse'
        ];
    }

    public function getType() {
        return 'sfh';
    }

    /**
     * @param Parser $parser
     * @param $frame
     * @param $args
     * @return array
     * @throws Exception
     */
    public static function getResult( Parser $parser, $frame, $args ) {
        GlobalFunctions::fetchSemanticArrays();

        $output = GlobalFunctions::getValue( $args, $frame, $parser, 5 );
        var_dump($output);
        return [ $output, 'noparse' => false, 'isHTML' => false ];
    }

}