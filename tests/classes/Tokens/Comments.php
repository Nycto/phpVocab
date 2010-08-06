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
class test_classes_Tokens_Comments extends \vc\Test\TestCase
{

    /**
     * Asserts that a comment doesn't contain any data
     *
     * @return NULL
     */
    public function assertEmptyComment ( $value )
    {
        $this->assertEquals( new \vc\Data\Comment, $value );
    }

    /**
     * Asserts that a value is a valid comment
     *
     * @return NULL
     */
    public function assertComment ( $comment, $value )
    {
        $this->assertEquals( new \vc\Data\Comment( $comment ), $value );
    }

    public function test_BasicTracking ()
    {
        $reader = new \vc\Tokens\Comments(
            new \vc\Parser\Comment,
            $this->oneTokenReader()
                ->thenADocComment('Comment')->thenAClass
        );

        $this->assertEmptyComment( $reader->getComment() );

        $this->assertHasToken( T_DOC_COMMENT, $reader );

        $this->assertHasToken( T_CLASS, $reader );
        $this->assertComment( 'Comment', $reader->getComment() );

        $this->assertEmptyComment( $reader->getComment() );

        $this->assertEndOfTokens( $reader );
    }

    public function testCommentInvalidedByToken ()
    {
        $reader = new \vc\Tokens\Comments(
            new \vc\Parser\Comment,
            $this->oneTokenReader()
                ->thenADocComment('Comment')->thenASemicolon
        );

        $this->assertEmptyComment( $reader->getComment() );
        $this->assertHasToken( T_DOC_COMMENT, $reader );

        $this->assertComment( 'Comment', $reader->getComment() );

        $this->assertHasToken( \vc\Tokens\Token::T_SEMICOLON, $reader );
        $this->assertEmptyComment( $reader->getComment() );
    }

    public function testPeekAtToken ()
    {
        $comments = new \vc\Tokens\Comments(
            new \vc\Parser\Comment,
            $this->oneTokenReader()->thenAClass
        );

        $this->assertIsTokenOf( T_CLASS, $comments->peekAtToken() );
    }

}

?>