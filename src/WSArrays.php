<?php

/**
 *
 * Initialization file for WSArrays.
 *
 * @license GPL-2.0-or-later
 * @version: 0.6.1.1
 *
 * @author Xxmarijnw <marijn@wikibase.nl>
 *
 */

if (!defined( 'MEDIAWIKI' ) ) {
    die();
};

require 'GlobalFunctions.class.php';

$GLOBALS['smwgResultFormats']['complexarray'] = 'SMW\Query\ResultPrinters\ComplexArrayPrinter';

/**
 * Class WSArrays
 *
 * Defines all parser functions.
 *
 * @extends GlobalFunctions
 */
class WSArrays extends GlobalFunctions {
    const INCLUDE_DIR = 'classes/';

    /**
     * This variable holds all defined arrays. If an array is defined called "array", the array will be stored in self::$arrays["array"].
     *
     * @var array
     */
    public static $arrays = [];

    /**
     * Holds defined options.
     *
     * @var array
     */
    public static $options = [
        "max_defined_arrays" => -1
    ];

    /**
     * This function is called on every page.
     *
     * @param Parser $parser
     * @return boolean
     * @throws Exception
     */
    public static function onParserFirstCallInit( Parser $parser ) {
        global $wfMaxDefinedArrays;
        if(is_numeric($wfMaxDefinedArrays) && $wfMaxDefinedArrays >= 0) {
            self::$options['max_defined_arrays'] = $wfMaxDefinedArrays;
        }

        try {
            spl_autoload_register('WSArrays::autoload');

            $function_hooks = [
                [
                    "class" => "ComplexArrayDefine",
                    "hooks" => [
                        "complexarraydefine",
                        "cadefine"
                    ]
                ],
                [
                    "class" => "ComplexArrayPrint",
                    "hooks" => [
                        "complexarrayprint",
                        "caprint"
                    ]
                ],
                [
                    "class" => "ComplexArraySize",
                    "hooks" => [
                        "complexarraysize",
                        "casize"
                    ]
                ],
                [
                    "class" => "ComplexArraySearch",
                    "hooks" => [
                        "complexarraysearch",
                        "casearch"
                    ]
                ],
                [
                    "class" => "ComplexArraySearchArray",
                    "hooks" => [
                        "complexarraysearcharray",
                        "casearcharray",
                        "casearcha"
                    ]
                ],
                [
                    "class" => "ComplexArrayAddValue",
                    "hooks" => [
                        "complexarrayaddvalue",
                        "caaddvalue",
                        "caadd",
                        "caaddv"
                    ]
                ],
                [
                    "class" => "ComplexArraySlice",
                    "hooks" => [
                        "complexarrayslice",
                        "caslice"
                    ]
                ],
                [
                    "class" => "ComplexArrayCut",
                    "hooks" => [
                        "complexarraycut",
                        "cacut"
                    ]
                ],
                [
                    "class" => "ComplexArrayUnique",
                    "hooks" => [
                        "complexarrayunique",
                        "caunique"
                    ]
                ],
                [
                    "class" => "ComplexArrayReset",
                    "hooks" => [
                        "complexarrayreset",
                        "careset",
                        "caclear"
                    ]
                ],
                [
                    "class" => "ComplexArrayMerge",
                    "hooks" => [
                        "complexarraymerge",
                        "camerge"
                    ]
                ],
                [
                    "class" => "ComplexArraySort",
                    "hooks" => [
                        "complexarraysort",
                        "casort"
                    ]
                ],
                [
                    "class" => "ComplexArrayMapTemplate",
                    "hooks" => [
                        "complexarraymaptemplate",
                        "camaptemplate",
                        "camapt",
                        "catemplate",
                        "camap"
                    ]
                ],
                [
                    "class" => "ComplexArrayPushValue",
                    "hooks" => [
                        "complexarraypushvalue",
                        "complexarraypush",
                        "capush"
                    ]
                ],
                [
                    "class" => "ComplexArrayExtract",
                    "hooks" => [
                        "complexarrayextract",
                        "caextract"
                    ]
                ],
                [
                    "class" => "ComplexArrayPushArray",
                    "hooks" => [
                        "complexarraypusharray",
                        "capusharray"
                    ]
                ],
            ];

            WSArrays::setHooks($parser, $function_hooks);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }

    protected static function setHooks(Parser $parser, $function_hooks) {
        if(!is_array($function_hooks)) return false;

        foreach($function_hooks as $function_hook) {
            $class = $function_hook['class'];
            $hooks = $function_hook['hooks'];

            foreach($hooks as $hook) {
                $parser->setFunctionHook( $hook, [$class, 'defineParser'] );
            }
        }

        return true;
    }

    protected static function autoload($class) {
        $file = __DIR__ . '/' . WSArrays::INCLUDE_DIR . $class . '.class.php';

        if(file_exists($file)) {
            require $file;
        }
    }

}
