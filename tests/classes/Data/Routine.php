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
 * Unit test
 */
class test_classes_Data_Routine extends \vc\Test\TestCase
{

    public function testConstruct ()
    {
        $routine = $this->getMockForAbstractClass('\vc\Data\Routine', array(123));
        $this->assertSame( 123, $routine->getLine() );
        $this->assertEquals( new \vc\Data\Comment, $routine->getComment() );

        $comment = new \vc\Data\Comment;
        $routine = $this->getMockForAbstractClass(
            '\vc\Data\Routine',
            array(123, $comment)
        );
        $this->assertSame( 123, $routine->getLine() );
        $this->assertSame( $comment, $routine->getComment() );
    }

    public function testNameAccess ()
    {
        $routine = $this->getMockForAbstractClass('\vc\Data\Routine', array(123));

        $this->assertNull( $routine->getName() );
        $this->assertSame( $routine, $routine->setName("method") );
        $this->assertSame( "method", $routine->getName() );
    }

    public function testArgAccess ()
    {
        $routine = $this->getMockForAbstractClass('\vc\Data\Routine', array(123));
        $this->assertSame( array(), $routine->getArgs() );

        $arg1 = new \vc\Data\Arg;
        $arg2 = new \vc\Data\Arg;
        $this->assertSame( $routine, $routine->setArgs(array($arg1, $arg2)) );
        $this->assertSame( array($arg1, $arg2), $routine->getArgs() );
    }

    public function testReturnRefAccess ()
    {
        $routine = $this->getMockForAbstractClass('\vc\Data\Routine', array(123));
        $this->assertFalse( $routine->getReturnRef() );

        $this->assertSame( $routine, $routine->setReturnRef(TRUE) );
        $this->assertTrue( $routine->getReturnRef() );
    }

}

