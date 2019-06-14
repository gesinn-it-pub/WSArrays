<?php

class WSComplexArrays {
    /**
     * This function is called on every page.
     *
     * @param Parser $parser
     * @return boolean
     * @throws Exception
     */
    public static function onParserFirstCallInit( Parser $parser ) {
        $parser->setFunctionHook( 'complexarraydefine', [ComplexArrayDefine::class, 'defineComplexArrayDefine'] );
        $parser->setFunctionHook( 'complexarrayprintvalue', [ComplexArrayPrintValue::class, 'defineComplexArrayPrintValue'] );

        return true;
    }
}
