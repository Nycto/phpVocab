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

use \vc\Tokens\Token as Token;

require_once rtrim( __DIR__, "/" ) ."/../../setup.php";

/**
 * Unit test for running all the tests
 */
class test_classes_Tokens_Token extends \vc\Test\TestCase
{

    public function testGetTokenName_FromABuiltInToken ()
    {
        $this->assertEquals(
            "T_ECHO",
            Token::getTokenName( Token::T_ECHO )
        );
    }

    public function testGetTokenName_FromACustomToken ()
    {
        $this->assertEquals(
            "T_EQUALS",
            Token::getTokenName( Token::T_EQUALS )
        );

        $this->assertEquals(
            "T_EQUALS",
            Token::getTokenName( Token::T_EQUALS )
        );
    }

    public function testGetTokenName_UnknownToken ()
    {
        $this->assertNull(
            Token::getTokenName( 50000 )
        );
    }

    public function testConstruct ()
    {
        $token = new Token(Token::T_ECHO, "echo", 1);
        $this->assertSame( Token::T_ECHO, $token->getType() );
        $this->assertSame( "echo", $token->getcontent() );
        $this->assertSame( 1, $token->getLine() );
        $this->assertSame( "T_ECHO", $token->getName() );
    }

    public function testFromArray ()
    {
        $this->assertEquals(
            new Token(Token::T_ECHO, "echo", 1),
            Token::fromArray(array(Token::T_ECHO, "echo", 1))
        );
    }

    public function testIs ()
    {
        $token = new Token(Token::T_ECHO, "echo", 1);
        $this->assertTrue( $token->is( Token::T_ECHO ) );
        $this->assertTrue( $token->is(array(Token::T_ECHO, Token::T_CLASS)) );
        $this->assertFalse( $token->is( Token::T_CLASS ) );
        $this->assertFalse( $token->is(array(Token::T_USE, Token::T_CLASS)) );
    }

    public function testExpect ()
    {
        $token = new Token(Token::T_ECHO, "echo", 1);

        $this->assertSame( $token, $token->expect(Token::T_ECHO) );
        $this->assertSame( $token, $token->expect(array(Token::T_USE, Token::T_ECHO)) );

        try {
            $token->expect( Token::T_CLASS );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\UnexpectedToken $err ) {}

        try {
            $token->expect(array(Token::T_USE, Token::T_CLASS));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\UnexpectedToken $err ) {}
    }

    public function testToArray ()
    {
        $token = new Token(Token::T_ECHO, "echo", 1);
        $this->assertEquals(
            array(Token::T_ECHO, "echo", 1),
            $token->toArray()
        );
    }

}

?>