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

require_once rtrim( __DIR__, "/" ) ."/../../../setup.php";

/**
 * Unit test
 */
class test_classes_Data_Type_Cls extends \vc\Test\TestCase
{

    public function testFinalAccess ()
    {
        $cls = $this->getMockForAbstractClass(
            '\vc\Data\Type\Cls',
            array(123, new \vc\Data\Comment)
        );

        $this->assertFalse( $cls->getFinal() );
        $this->assertSame( $cls, $cls->setFinal(TRUE) );
        $this->assertTrue( $cls->getFinal() );
    }

    public function testAbstractAccess ()
    {
        $cls = $this->getMockForAbstractClass(
            '\vc\Data\Type\Cls',
            array(123, new \vc\Data\Comment)
        );

        $this->assertFalse( $cls->getAbstract() );
        $this->assertSame( $cls, $cls->setAbstract(TRUE) );
        $this->assertTrue( $cls->getAbstract() );
    }

    public function testExtendsAccess ()
    {
        $cls = $this->getMockForAbstractClass(
            '\vc\Data\Type\Cls',
            array(123, new \vc\Data\Comment)
        );

        $this->assertNull( $cls->getExtends() );
        $this->assertSame( $cls, $cls->setExtends('parent') );
        $this->assertSame( 'parent', $cls->getExtends() );
    }

    public function testIFaceAccess ()
    {
        $cls = $this->getMockForAbstractClass(
            '\vc\Data\Type\Cls',
            array(123, new \vc\Data\Comment)
        );

        $this->assertSame( array(), $cls->getIFaces() );
        $this->assertSame( $cls, $cls->addIFace('parent') );
        $this->assertSame( array('parent'), $cls->getIFaces() );
        $this->assertSame( $cls, $cls->addIFace('parent2') );
        $this->assertSame( array('parent', 'parent2'), $cls->getIFaces() );
    }

}

?>