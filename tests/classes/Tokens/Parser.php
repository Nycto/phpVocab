<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of phpVocab.
 *
 * phpVocab is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * phpVocab is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with phpVocab. If not, see <http://www.phpVocab.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RoundEights.com>
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 */

require_once rtrim( __DIR__, "/" ) ."/../../setup.php";

/**
 * Unit test for running all the tests
 */
class test_Tokens_Parser extends PHPUnit_Framework_TestCase
{

    public function testIterate ()
    {
        $parser = new \vc\Tokens\Parser(
            new \r8\Stream\In\String(
                "<?php echo 'content'; ?>"
            )
        );

        $result = array (
            array(368, '<?php ', 1),
            array(316, 'echo', 1),
            array(371, ' ', 1),
            array(315, "'content'", 1),
            array(-106, ';', 1),
            array(371, ' ', 1),
            array(370, '?>', 1),
        );

        \r8\Test\Constraint\Iterator::assert( $result, $parser );
        \r8\Test\Constraint\Iterator::assert( $result, $parser );
        \r8\Test\Constraint\Iterator::assert( $result, $parser );
    }

    public function testIterate_CustomTokens ()
    {
        $parser = new \vc\Tokens\Parser(
            new \r8\Stream\In\String(
                '<?php if(!$$v[0]=1<5){~1%1*1+1-1/1&1^1|1>0?:`w`.@a("$a",1);}?>'
            )
        );

        $result = array (
            array(368, '<?php ', 1), array(301, 'if', 1), array(-113, '(', 1),
            array(-108, '!', 1), array(-124, '$', 1), array(309, '$v', 1),
            array(-115, '[', 1), array(305, '0', 1), array(-116, ']', 1),
            array(-101, '=', 1), array(305, '1', 1), array(-100, '<', 1),
            array(305, '5', 1), array(-114, ')', 1), array(-117, '{', 1),
            array(-125, '~', 1), array(305, '1', 1), array(-122, '%', 1),
            array(305, '1', 1), array(-120, '*', 1), array(305, '1', 1),
            array(-123, '+', 1), array(305, '1', 1), array(-104, '-', 1),
            array(305, '1', 1), array(-110, '/', 1), array(305, '1', 1),
            array(-121, '&', 1), array(305, '1', 1), array(-126, '^', 1),
            array(305, '1', 1), array(-103, '|', 1), array(305, '1', 1),
            array(-102, '>', 1), array(305, '0', 1), array(-109, '?', 1),
            array(-107, ':', 1), array(-127, '`', 1), array(314, 'w', 1),
            array(-127, '`', 1), array(-111, '.', 1), array(-119, '@', 1),
            array(307, 'a', 1), array(-113, '(', 1), array(-112, '"', 1),
            array(309, '$a', 1), array(-112, '"', 1), array(-105, ',', 1),
            array(305, '1', 1), array(-114, ')', 1), array(-106, ';', 1),
            array(-118, '}', 1), array(370, '?>', 1),
        );

        \r8\Test\Constraint\Iterator::assert( $result, $parser );
        \r8\Test\Constraint\Iterator::assert( $result, $parser );
        \r8\Test\Constraint\Iterator::assert( $result, $parser );
    }

    public function testIterate_Empty ()
    {
        $parser = new \vc\Tokens\Parser( new \r8\Stream\In\Void );

        \r8\Test\Constraint\Iterator::assert( array(), $parser );
        \r8\Test\Constraint\Iterator::assert( array(), $parser );
        \r8\Test\Constraint\Iterator::assert( array(), $parser );
    }

}

?>