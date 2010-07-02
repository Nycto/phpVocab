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
class test_Tokens_Token extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $token = new \vc\Tokens\Token(315, "'content'", 1);
        $this->assertSame( 315, $token->getType() );
        $this->assertSame( "'content'", $token->getcontent() );
        $this->assertSame( 1, $token->getLine() );
    }

    public function testFromArray ()
    {
        $this->assertEquals(
            new \vc\Tokens\Token(315, "'content'", 1),
            \vc\Tokens\Token::fromArray(array(315, "'content'", 1))
        );
    }

    public function testGetName_FromABuiltInToken ()
    {
        $token = new \vc\Tokens\Token(315, "'content'", 1);
        $this->assertEquals(
            "T_CONSTANT_ENCAPSED_STRING",
            $token->getName()
        );
    }

    public function testGetName_FromACustomToken ()
    {
        $token = new \vc\Tokens\Token(\vc\Tokens\Token::T_EQUALS, "'content'", 1);
        $this->assertEquals(
            "T_EQUALS",
            $token->getName()
        );
    }

    public function testGetName_UnknownToken ()
    {
        $token = new \vc\Tokens\Token(50000, "'content'", 1);
        $this->assertNull( $token->getName() );
    }

    public function testToArray ()
    {
        $token = new \vc\Tokens\Token(315, "'content'", 1);
        $this->assertEquals(
            array(315, "'content'", 1),
            $token->toArray()
        );
    }

}

?>