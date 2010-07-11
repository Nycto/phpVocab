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
class test_classes_Tokens_Comments extends PHPUnit_Framework_TestCase
{

    public function test_BasicTracking ()
    {
        $token1 = new \vc\Tokens\Token( T_DOC_COMMENT, '/** */', 1 );
        $token2 = new \vc\Tokens\Token( T_CLASS, 'class', 1 );

        $inner = $this->getMock('\vc\iface\Tokens\Reader');
        $inner->expects( $this->exactly(2) )->method( "hasToken" )
            ->will( $this->returnValue( TRUE ) );
        $inner->expects( $this->at(1) )->method( "nextToken" )
            ->will( $this->returnValue($token1) );
        $inner->expects( $this->at(3) )->method( "nextToken" )
            ->will( $this->returnValue($token2) );

        $comments = new \vc\Tokens\Comments( $inner );
        $this->assertNull( $comments->getComment() );

        $this->assertTrue( $comments->hasToken() );
        $this->assertSame( $token1, $comments->nextToken() );

        $this->assertTrue( $comments->hasToken() );
        $this->assertSame( $token2, $comments->nextToken() );
        $this->assertSame( '/** */', $comments->getComment() );

        $this->assertSame( $comments, $comments->consumeComment() );
        $this->assertNull( $comments->getComment() );
    }

    public function testReinstateToken ()
    {
        $inner = $this->getMock('\vc\iface\Tokens\Reader');
        $inner->expects( $this->once() )->method( "reinstateToken" );

        $comments = new \vc\Tokens\Comments( $inner );
        $this->assertSame( $comments, $comments->reinstateToken() );
    }

}

?>