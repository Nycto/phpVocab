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

namespace vc\Test;

/**
 * The base test case class
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Returns an access object loaded with the given reader and comment
     *
     * @return \vc\Tokens\Access
     */
    public function getAccessParserWithComment (
        \vc\Data\Comment $comment,
        \vc\iface\Tokens\Reader $reader
    ) {
        $comParse = $this->getMock('\vc\iface\Tokens\Comments');
        $comParse->expects( $this->any() )->method( "getComment" )
            ->will( $this->returnValue( $comment ) );

        return new \vc\Tokens\Access(
            $reader,
            r8(new \vc\Tokens\Search($reader))
                ->setTokenMask(array(\vc\Tokens\Token::T_WHITESPACE)),
            $comParse
        );
    }

    /**
     * Asserts that an Enum equals the given value
     *
     * @param String|Integer $expected
     * @param Mixed $enum
     * @return NULL
     */
    public function assertEnum ( $expected, $enum )
    {
        $this->assertThat( $enum, $this->isInstanceOf( '\r8\Enum' ) );
        $this->assertSame( $expected, $enum->getValue() );
    }

    /**
     * Returns a stub of the given class that won't call it's constructor
     *
     * @param String $class
     * @return Mixed
     */
    public function getStub ( $class )
    {
        return $this->getMock( $class, array(), array(), '', FALSE );
    }

    /**
     * Returns a new token reader
     *
     * @return \vc\iface\Tokens\Reader
     */
    public function oneTokenReader ()
    {
        return new \vc\Test\TokenReader;
    }

    /**
     * Asserts that a value is a token of the given type
     */
    public function assertIsTokenOf ( $type, $token )
    {
        $this->assertThat(
            $token,
            $this->isInstanceOf('\vc\Tokens\Token'),
            "Value is not a Token"
        );
        $this->assertEquals(
            \vc\Tokens\Token::getTokenName($type),
            $token->getName(),
            "Token is not of the correct type"
        );
    }

    /**
     * Asserts that a token parser has a next token of the given type
     */
    public function assertHasToken ( $type, $tokens )
    {
        $this->assertThat(
            $tokens,
            $this->isInstanceOf('\vc\iface\Tokens\Reader'),
            "Value is not a Token Reader"
        );
        $this->assertTrue(
            $tokens->hasToken(),
            "Token Reader does not have any tokens remaining"
        );
        $peek = $tokens->peekAtToken();
        $this->assertIsTokenOf( $type, $peek );
        $this->assertSame(
            $peek,
            $tokens->popToken(),
            "Popped token does not match peeked at token"
        );
    }

    /**
     * Asserts that there are no more tokens in this set
     */
    public function assertEndOfTokens ( $tokens )
    {
        $this->assertThat(
            $tokens,
            $this->isInstanceOf('\vc\iface\Tokens\Reader'),
            "Value is not a Token Reader"
        );
        $this->assertFalse(
            $tokens->hasToken(),
            "Token Reader should not have any tokens remaining"
        );
        $this->assertNull(
            $tokens->popToken(),
            "Token Reader should return NULL when it has no more tokens"
        );
    }

}

?>