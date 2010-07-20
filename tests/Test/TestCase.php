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
            $type,
            $token->getType(),
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
        $this->assertIsTokenOf( $type, $tokens->popToken() );
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