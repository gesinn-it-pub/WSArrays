<?php

namespace WSArrays;

require 'include.php';

class WSArrays {
    /**
     * This function is called on every page.
     *
     * @param Parser $parser
     * @return boolean
     * @throws Exception
     */
    public static function onParserFirstCallInit( Parser $parser ) {
        $parser->setFunctionHook( 'complexarraydefine', [ComplexArrayDefine::class, 'defineParser'] );
        $parser->setFunctionHook( 'complexarrayprintvalue', [ComplexArrayPrint::class, 'defineParser'] );

        return true;
    }
}
