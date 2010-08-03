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
class test_classes_Data_Type extends \vc\Test\TestCase
{

    public function testConstruct ()
    {
        $comment = new \vc\Data\Comment;

        $type = $this->getMockForAbstractClass(
            '\vc\Data\Type',
            array(123, $comment)
        );

        $this->assertSame( 123, $type->getLine() );
        $this->assertSame( $comment, $type->getComment() );
    }

    public function testNameAccess ()
    {
        $type = $this->getMockForAbstractClass('\vc\Data\Type', array(123));

        $this->assertNull( $type->getName() );
        $this->assertSame( $type, $type->setName("method") );
        $this->assertSame( "method", $type->getName() );
    }

    public function testMethodAccess ()
    {
        $type = $this->getMockForAbstractClass('\vc\Data\Type', array(123));
        $this->assertSame( array(), $type->getMethods() );

        $meth1 = new \vc\Data\Routine\Method(1);
        $this->assertSame( $type, $type->addMethod($meth1) );
        $this->assertSame( array($meth1), $type->getMethods() );

        $meth2 = new \vc\Data\Routine\Method(1);
        $this->assertSame( $type, $type->addMethod($meth2) );
        $this->assertSame( array($meth1, $meth2), $type->getMethods() );
    }

    public function testConstantAccess ()
    {
        $type = $this->getMockForAbstractClass('\vc\Data\Type', array(123));
        $this->assertSame( array(), $type->getConstants() );

        $const1 = new \vc\Data\Constant(1);
        $this->assertSame( $type, $type->addConstant($const1) );
        $this->assertSame( array($const1), $type->getConstants() );

        $const2 = new \vc\Data\Constant(1);
        $this->assertSame( $type, $type->addConstant($const2) );
        $this->assertSame( array($const1, $const2), $type->getConstants() );
    }

}

?>