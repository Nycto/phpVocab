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
class test_classes_Tokens_Access extends \vc\Test\TestCase
{

    public function testTokenReader ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAClass()
        );

        $this->assertTrue( $access->hasToken() );
        $this->assertIsTokenOf( Token::T_CLASS, $access->peekAtToken() );
        $this->assertIsTokenOf( Token::T_CLASS, $access->popToken() );
        $this->assertEndOfTokens( $access );
    }

    public function testTokenSearch ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $reader = $this->oneTokenReader()
                ->thenAClass()->thenAnOpenBlock()
        );

        $this->assertIsTokenOf(
            Token::T_BLOCK_OPEN,
            $access->findAllowing(
                array(Token::T_BLOCK_OPEN),
                array(Token::T_CLASS)
            )
        );
    }

    public function testTokenComments ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $reader = $this->oneTokenReader()
                ->thenADocComment('test')->thenAClass()
        );

        $this->assertHasToken( Token::T_DOC_COMMENT, $access );
        $this->assertHasToken( Token::T_CLASS, $access );

        $this->assertEquals(
            new \vc\Data\Comment('test'),
            $access->getComment()
        );
    }

    public function testUntilTokens ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenAClass()->thenAnOpenBlock()
                ->thenAFunction()->thenACloseBlock()
        );

        $until = $access->untilTokens(array( Token::T_FUNCTION ));
        $this->assertThat( $until, $this->isInstanceOf('\vc\Tokens\Access') );

        $this->assertHasToken( Token::T_CLASS, $until );
        $this->assertHasToken( Token::T_BLOCK_OPEN, $until );
        $this->assertEndOfTokens( $until );
    }

    public function testUntilBlockEnds ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenAFunction()->thenACloseBlock()->thenAClass()
        );

        $until = $access->untilBlockEnds();
        $this->assertThat( $until, $this->isInstanceOf('\vc\Tokens\Access') );

        $this->assertHasToken( Token::T_FUNCTION, $until );
        $this->assertHasToken( Token::T_BLOCK_CLOSE, $until );
        $this->assertEndOfTokens( $until );
    }

}

?>