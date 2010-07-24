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
class test_classes_Tokens_Token extends \vc\Test\TestCase
{

    public function testGetTokenName_FromABuiltInToken ()
    {
        $this->assertEquals(
            "T_CONSTANT_ENCAPSED_STRING",
            \vc\Tokens\Token::getTokenName( \T_CONSTANT_ENCAPSED_STRING )
        );
    }

    public function testGetTokenName_FromACustomToken ()
    {
        $this->assertEquals(
            "T_EQUALS",
            \vc\Tokens\Token::getTokenName( \vc\Tokens\Token::T_EQUALS )
        );

        $this->assertEquals(
            "T_EQUALS",
            \vc\Tokens\Token::getTokenName( \vc\Tokens\Token::T_EQUALS )
        );
    }

    public function testGetTokenName_UnknownToken ()
    {
        $this->assertNull(
            \vc\Tokens\Token::getTokenName( 50000 )
        );
    }

    public function testConstruct ()
    {
        $token = new \vc\Tokens\Token(315, "'content'", 1);
        $this->assertSame( 315, $token->getType() );
        $this->assertSame( "'content'", $token->getcontent() );
        $this->assertSame( 1, $token->getLine() );
        $this->assertSame( "T_CONSTANT_ENCAPSED_STRING", $token->getName() );
    }

    public function testFromArray ()
    {
        $this->assertEquals(
            new \vc\Tokens\Token(315, "'content'", 1),
            \vc\Tokens\Token::fromArray(array(315, "'content'", 1))
        );
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