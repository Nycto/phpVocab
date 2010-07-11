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
class test_classes_Data_Routine extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $comment = new \vc\Data\Comment("summary", "details");

        $routine = $this->getMockForAbstractClass(
            '\vc\Data\Routine',
            array(123, $comment)
        );

        $this->assertSame( 123, $routine->getLine() );
        $this->assertSame( $comment, $routine->getComment() );
    }

    public function testNameAccess ()
    {
        $routine = $this->getMockForAbstractClass(
            '\vc\Data\Routine',
            array(123, new \vc\Data\Comment("summary", "details"))
        );

        $this->assertNull( $routine->getName() );
        $this->assertSame( $routine, $routine->setName("method") );
        $this->assertSame( "method", $routine->getName() );
    }

    public function testArgAccess ()
    {
        $routine = $this->getMockForAbstractClass(
            '\vc\Data\Routine',
            array(123, new \vc\Data\Comment("summary", "details"))
        );

        $this->assertSame( array(), $routine->getArgs() );

        $arg1 = new \vc\Data\Arg("arg1");
        $this->assertSame( $routine, $routine->addArg($arg1) );
        $this->assertSame( array($arg1), $routine->getArgs() );

        $arg2 = new \vc\Data\Arg("arg2");
        $this->assertSame( $routine, $routine->addArg($arg2) );
        $this->assertSame( array($arg1, $arg2), $routine->getArgs() );
    }

}

?>