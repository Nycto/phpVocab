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
class test_classes_Tokens_Access extends \vc\Test\TestCase
{

    /**
     * Returns a test token reader with sample data loaded in it
     *
     * @return \vc\Token\Access
     */
    public function getTestReader ()
    {
        return new \vc\Tokens\Access(
            $this->oneTokenReader()->thenAnOpenTag()->thenAnEcho()
                ->thenSomeSpace()->thenAString("content")
                ->thenASemiColon()->thenACloseTag()
        );
    }

    public function testFind_EmptyTokenSet ()
    {
        $reader = new \vc\Tokens\Access( $this->oneTokenReader() );

        $this->assertNull(
            $reader->find(array(Token::T_CLASS))
        );
    }

    public function testFind_TokenGetsFound ()
    {
        $reader = $this->getTestReader();

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $reader->find(array( Token::T_ECHO, Token::T_SEMICOLON ))
        );

        $this->assertIsTokenOf(
            Token::T_SEMICOLON,
            $reader->find(array( Token::T_ECHO, Token::T_SEMICOLON ))
        );

        $this->assertNull(
            $reader->find(array( Token::T_ECHO, Token::T_SEMICOLON ))
        );
    }

    public function testFindExcluding_EmptyTokenSet ()
    {
        $reader = new \vc\Tokens\Access( $this->oneTokenReader() );

        $this->assertNull(
            $reader->find(array(Token::T_CLASS))
        );
    }

    public function testFindExcluding_TokenGetsFound ()
    {
        $reader = $this->getTestReader();

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $reader->findExcluding(array(
                Token::T_WHITESPACE,
                Token::T_OPEN_TAG
            ))
        );

        $this->assertIsTokenOf(
            Token::T_SEMICOLON,
            $reader->findExcluding(array(
                Token::T_WHITESPACE,
                Token::T_CONSTANT_ENCAPSED_STRING
            ))
        );

        $this->assertNull(
            $reader->findExcluding(array( Token::T_CLOSE_TAG ))
        );
    }

    public function testFindAllowing_EmptyTokenSet ()
    {
        $reader = new \vc\Tokens\Access( $this->oneTokenReader() );

        $this->assertNull(
            $reader->findAllowing(array(Token::T_CLASS))
        );
    }

    public function testFindAllowing_TokenGetsFound ()
    {
        $reader = $this->getTestReader();

        $this->assertIsTokenOf(
            Token::T_ECHO,
            $reader->findAllowing(
                array( Token::T_ECHO, Token::T_SEMICOLON ),
                array( Token::T_OPEN_TAG, Token::T_CONSTANT_ENCAPSED_STRING )
            )
        );

        $this->assertIsTokenOf(
            Token::T_SEMICOLON,
            $reader->findAllowing(
                array( Token::T_ECHO, Token::T_SEMICOLON ),
                array( Token::T_WHITESPACE, Token::T_CONSTANT_ENCAPSED_STRING )
            )
        );

        $this->assertNull(
            $reader->findAllowing(
                array( Token::T_CLASS ),
                array( Token::T_CLOSE_TAG )
            )
        );
    }

    public function testFindAllowing_UnexpectedToken ()
    {
        $reader = $this->oneTokenReader()->thenAnOpenTag()->thenAnEcho()
            ->thenSomeSpace()->thenAString("content")
            ->thenASemiColon()->thenACloseTag();

        $access = new \vc\Tokens\Access( $reader );

        try {
            $access->findAllowing(
                array( Token::T_CLOSE_TAG, Token::T_SEMICOLON ),
                array( Token::T_OPEN_TAG, Token::T_WHITESPACE )
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\UnexpectedToken $err ) {}

        $this->assertHasToken( Token::T_ECHO, $reader );
    }

}

?>