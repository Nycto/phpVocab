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

use \vc\Tokens\Token as Token;

/**
 * Unit tests
 */
class test_classes_Parser_Brackets extends \vc\Test\TestCase
{

    public function testParseParens_OpenParensAsFirstToken ()
    {
        $reader = $this->oneTokenReader()->thenOpenParens
            ->thenSomeSpace->thenAFunction->thenSomeSpace
            ->thenAName("MyFunc")->thenSomeSpace->thenCloseParens;

        $this->assertSame(
            ' function MyFunc ',
            r8(new \vc\Parser\Brackets)->parseParens( $reader )
        );
    }

    public function testParseParens_WithoutCloseParens ()
    {
        $reader = $this->oneTokenReader()
            ->thenSomeSpace->thenAFunction->thenSomeSpace
            ->thenAName("MyFunc")->thenSomeSpace;

        $this->assertSame(
            ' function MyFunc ',
            r8(new \vc\Parser\Brackets)->parseParens( $reader )
        );
    }

    public function testParseParens_NestedParens ()
    {
        $reader = $this->oneTokenReader()->thenOpenParens
            ->thenSomeSpace->thenAFunction->thenSomeSpace
            ->thenAName("MyFunc")->thenOpenParens
            ->thenAnArrayValue(array(array(), 2, 3))->thenCloseParens
            ->thenSomeSpace->thenCloseParens;

        $this->assertSame(
            " function MyFunc(array ( 0 => array ( ), 1 => 2, 2 => 3,)) ",
            r8(new \vc\Parser\Brackets)->parseParens( $reader )
        );
    }

    public function testParseCurlies ()
    {
        $reader = $this->oneTokenReader()
            ->thenAFunction->thenSomeSpace
            ->thenAName("MyFunc")->thenOpenParens->thenCloseParens
            ->thenAnOpenBlock->thenACloseBlock
            ->thenSomeSpace->thenAFunction->thenSomeSpace
            ->thenAName("OtherFunc")->thenOpenParens->thenCloseParens
            ->thenAnOpenBlock->thenACloseBlock->thenSomeSpace
            ->thenACloseBlock;

        $this->assertSame(
            "function MyFunc(){} function OtherFunc(){} ",
            r8(new \vc\Parser\Brackets)->parseCurlies( $reader )
        );
    }

}

?>