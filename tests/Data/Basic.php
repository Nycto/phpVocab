<?php
/**
 * File Comment
 */

namespace sample\ns;

use sample;
use sample\SampleParent as SampleParent;

const CONST_STRING = 'test';
const CONST_INT = 123;
const CONST_FLOAT = 3.14;

/**
 * A function comment
 *
 * @return Integer
 */
function someFunction ()
{
    return 1234;
}

/**
 * Class Comment
 */
class SampleClass extends SampleParent implements \SampleIFace, \SampleIFace2
{

    /**
     * Constant comment
     */
    const CLASS_CONST_STRING = 'test';
    const CLASS_CONST_INT = 123;
    const CLASS_CONST_FLOAT = 3.14;

    /**
     * Property Comment
     *
     * @var NULL
     */
    private $propNoValue;

    /**
     * Property Comment
     *
     * @var Integer
     */
    private $propInt = 123;

    /**
     * A method without any arguments
     *
     * @return Boolean
     */
    public function getBoolean ()
    {
        return TRUE;
    }

    /**
     * A Comment for a method
     *
     * @param Array $arg Arg comment
     * @param String $arg2 A string
     * @return String
     */
    public function getData ( array $arg, $arg2 = "Default" )
    {
        return !empty($arg) ? "empty" : (string) $arg2;
    }

    /**
     * Another comment
     */
    public function dump ( &$ref, StdClass $arg1, \Anoth\erClass $arg2 = NULL )
    {
        echo "To String: {$ref}, ${arg1}, {$arg2}";
    }

}
