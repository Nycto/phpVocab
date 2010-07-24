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
                ->thenAFunction()->thenAnEcho()
                ->thenASemicolon()->thenACloseBlock()
        );

        $this->assertIsTokenOf(
            Token::T_BLOCK_OPEN,
            $access->find(array( Token::T_BLOCK_OPEN ))
        );

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $access->findExcluding(array( Token::T_FUNCTION ))
        );

        $this->assertIsTokenOf(
            Token::T_BLOCK_CLOSE,
            $access->findAllowing(
                array(Token::T_BLOCK_CLOSE),
                array(Token::T_SEMICOLON)
            )
        );
    }

}

?>