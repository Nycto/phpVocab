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
 * Unit test
 */
class test_classes_Tokens_Until extends \vc\Test\TestCase
{

    public function test_EmptyTokenStream ()
    {
        $reader = new \vc\Tokens\Until(
            array( Token::T_FUNCTION ),
            $this->oneTokenReader()
        );

        $this->assertEndOfTokens( $reader );
        $this->assertNull( $reader->peekAtToken() );
    }

    public function test_NoInterruptsInTokenStream ()
    {
        $reader = new \vc\Tokens\Until(
            array( Token::T_FUNCTION ),
            $this->oneTokenReader()->thenAnOpenBlock->thenACloseBlock
        );

        $this->assertHasToken( Token::T_CURLY_OPEN, $reader );
        $this->assertHasToken( Token::T_CURLY_CLOSE, $reader );
        $this->assertEndOfTokens( $reader );
    }

    public function test_StreamInterrupted ()
    {
        $reader = new \vc\Tokens\Until(
            array( Token::T_FUNCTION ),
            $this->oneTokenReader()->thenAnOpenBlock
                ->thenAFunction->thenACloseBlock
        );

        $this->assertHasToken( Token::T_CURLY_OPEN, $reader );
        $this->assertEndOfTokens( $reader );
    }

    public function testPeekAtToken ()
    {
        $reader = new \vc\Tokens\Until(
            array( Token::T_FUNCTION, Token::T_CLASS ),
            $this->oneTokenReader()->thenAnOpenBlock
                ->thenAClass->thenACloseBlock
        );

        $this->assertIsTokenOf( Token::T_CURLY_OPEN, $reader->peekAtToken() );
        $this->assertHasToken( Token::T_CURLY_OPEN, $reader );

        $this->assertNull( $reader->peekAtToken() );
        $this->assertEndOfTokens( $reader );
    }

}

