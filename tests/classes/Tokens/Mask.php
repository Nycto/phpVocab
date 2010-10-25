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

use vc\Tokens\Token as Token;

require_once rtrim( __DIR__, "/" ) ."/../../setup.php";

/**
 * Unit test
 */
class test_classes_Tokens_Mask extends \vc\Test\TestCase
{

    public function testParsing_TokenFoundWithoutSkipping ()
    {
        $access = $this->oneTokenReader()->thenAnEcho;

        $ignore = new \vc\Tokens\Mask( $access, array(Token::T_COMMENT) );

        $this->assertHasToken( Token::T_ECHO, $ignore );
    }

    public function testParsing_TokenFoundWhileSkipping ()
    {
        $access = $this->oneTokenReader()
            ->thenAComment('comment')->thenAComment('comment')
            ->thenAnEcho;

        $ignore = new \vc\Tokens\Mask( $access, array(Token::T_COMMENT) );

        $this->assertHasToken( Token::T_ECHO, $ignore );
    }

    public function testPopToken_NoTokenFound ()
    {
        $access = $this->oneTokenReader()
            ->thenAComment('comment')->thenAComment('comment');

        $ignore = new \vc\Tokens\Mask( $access, array(Token::T_COMMENT) );

        $this->assertEndOfTokens( $ignore );
    }

    public function testCommentsMask ()
    {
        $access = $this->oneTokenReader()
            ->thenAComment('comment')
            ->then( Token::T_ML_COMMENT, '/* comment */' )
            ->thenAnEcho;

        $ignore = \vc\Tokens\Mask::comments( $access );

        $this->assertThat( $ignore, $this->isInstanceOf( '\vc\Tokens\Mask' ) );
        $this->assertHasToken( Token::T_ECHO, $ignore );
    }

}

