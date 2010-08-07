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
 * Unit tests
 */
class test_classes_Tokens_Search extends \vc\Test\TestCase
{

    public function testCopy ()
    {
        $search = new \vc\Tokens\Search( $this->oneTokenReader() );

        $this->assertThat(
            $search->copy($this->oneTokenReader()),
            $this->isInstanceOf( '\vc\Tokens\Search' )
        );
    }

    public function testpeekToRequired_EmptyTokenSet ()
    {
        $reader = new \vc\Tokens\Search( $this->oneTokenReader() );

        try {
            $reader->peekToRequired(array(Token::T_CLASS));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\UnexpectedEnd $err ) {}
    }

    public function testpeekToRequired_TokenGetsFound ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho
                ->thenSomeSpace->thenAString("content")
                ->thenASemiColon->thenACloseTag;

        $search = new \vc\Tokens\Search( $reader );

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $search->peekToRequired(
                array( Token::T_ECHO, Token::T_SEMICOLON ),
                array( Token::T_OPEN_TAG, Token::T_CONSTANT_ENCAPSED_STRING )
            )
        );

        $this->assertHasToken( Token::T_ECHO, $reader );
    }

    public function testpeekToRequired_UnexpectedToken ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho
            ->thenSomeSpace->thenAString("content")
            ->thenASemiColon->thenACloseTag;

        $access = new \vc\Tokens\Search( $reader );

        try {
            $access->peekToRequired(
                array( Token::T_CLOSE_TAG, Token::T_SEMICOLON ),
                array( Token::T_OPEN_TAG, Token::T_WHITESPACE )
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\UnexpectedToken $err ) {}

        $this->assertHasToken( Token::T_ECHO, $reader );
    }

    public function testPeekToRequired_SharedTokenMask ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho;

        $search = new \vc\Tokens\Search( $reader );
        $this->assertSame( $search, $search->setTokenMask(
            array( Token::T_OPEN_TAG, Token::T_CONSTANT_ENCAPSED_STRING )
        ) );

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $search->peekToRequired(array(Token::T_ECHO, Token::T_SEMICOLON))
        );

        $this->assertHasToken( Token::T_ECHO, $reader );
    }

    public function testPeekTo_TokenFound ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho;

        $search = new \vc\Tokens\Search( $reader );

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $search->peekTo(
                array( Token::T_ECHO, Token::T_SEMICOLON ),
                array( Token::T_OPEN_TAG, Token::T_CONSTANT_ENCAPSED_STRING )
            )
        );

        $this->assertHasToken( Token::T_ECHO, $reader );
    }

    public function testPeekTo_TokenNotFound ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho
            ->thenSomeSpace->thenAString("content")
            ->thenASemiColon->thenACloseTag;

        $access = new \vc\Tokens\Search( $reader );

        $this->assertNull(
            $access->peekTo(
                array( Token::T_CLOSE_TAG, Token::T_SEMICOLON ),
                array( Token::T_OPEN_TAG, Token::T_WHITESPACE )
            )
        );
    }

    public function testPeekTo_EmptyTokenSet ()
    {
        $reader = new \vc\Tokens\Search( $this->oneTokenReader() );
        $this->assertNull( $reader->peekTo(array(Token::T_CLASS)) );
    }

    public function testFindRequired_EmptyTokenSet ()
    {
        $reader = new \vc\Tokens\Search( $this->oneTokenReader() );

        try {
            $reader->findRequired(array(Token::T_CLASS));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\UnexpectedEnd $err ) {}
    }

    public function testFindRequired_TokenGetsFound ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho
                ->thenSomeSpace->thenAString("content")
                ->thenASemiColon->thenACloseTag;

        $search = new \vc\Tokens\Search( $reader );

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $search->findRequired(
                array( Token::T_ECHO, Token::T_SEMICOLON ),
                array( Token::T_OPEN_TAG, Token::T_CONSTANT_ENCAPSED_STRING )
            )
        );

        $this->assertHasToken( Token::T_WHITESPACE, $reader );
    }

    public function testFindRequired_UnexpectedToken ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho
            ->thenSomeSpace->thenAString("content")
            ->thenASemiColon->thenACloseTag;

        $access = new \vc\Tokens\Search( $reader );

        try {
            $access->findRequired(
                array( Token::T_CLOSE_TAG, Token::T_SEMICOLON ),
                array( Token::T_OPEN_TAG, Token::T_WHITESPACE )
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\UnexpectedToken $err ) {}

        $this->assertHasToken( Token::T_ECHO, $reader );
    }

    public function testFindRequired_SharedTokenMask ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho;

        $search = new \vc\Tokens\Search( $reader );
        $this->assertSame( $search, $search->setTokenMask(
            array( Token::T_OPEN_TAG, Token::T_CONSTANT_ENCAPSED_STRING )
        ) );

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $search->findRequired(array(Token::T_ECHO, Token::T_SEMICOLON))
        );

        $this->assertEndOfTokens( $reader );
    }

    public function testFind_TokenFound ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho
            ->thenSomeSpace->thenAString("content")
            ->thenASemiColon->thenACloseTag;

        $access = new \vc\Tokens\Search( $reader );

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $access->find(
                array( Token::T_ECHO, Token::T_SEMICOLON ),
                array( Token::T_OPEN_TAG, Token::T_WHITESPACE )
            )
        );
    }

    public function testFind_TokenNotFound ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho
            ->thenSomeSpace->thenAString("content")
            ->thenASemiColon->thenACloseTag;

        $access = new \vc\Tokens\Search( $reader );

        $this->assertNull(
            $access->find(
                array( Token::T_CLOSE_TAG, Token::T_SEMICOLON ),
                array( Token::T_OPEN_TAG, Token::T_WHITESPACE )
            )
        );

        $this->assertHasToken( Token::T_ECHO, $reader );
    }

    public function testFind_SharedTokenMask ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho;

        $search = new \vc\Tokens\Search( $reader );
        $this->assertSame( $search, $search->setTokenMask(
            array( Token::T_OPEN_TAG, Token::T_CONSTANT_ENCAPSED_STRING )
        ) );

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $search->find(array(Token::T_ECHO, Token::T_SEMICOLON))
        );
        $this->assertEndOfTokens( $reader );
    }

    public function testPeekToSkipping_TokenFound ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho
            ->thenSomeSpace->thenAString("content")
            ->thenASemiColon->thenACloseTag;

        $access = new \vc\Tokens\Search( $reader );

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $access->peekToSkipping(array(Token::T_ECHO, Token::T_SEMICOLON))
        );
    }

    public function testPeekToSkipping_TokenNotFound ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag->thenAnEcho
            ->thenSomeSpace->thenAString("content")
            ->thenASemiColon->thenACloseTag;

        $access = new \vc\Tokens\Search( $reader );

        $this->assertNull(
            $access->peekToSkipping(array(Token::T_CLASS, Token::T_USE))
        );

        $this->assertEndOfTokens( $reader );
    }

}

?>