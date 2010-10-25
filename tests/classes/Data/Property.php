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
class test_classes_Data_Property extends \vc\Test\TestCase
{

    public function testConstruct ()
    {
        $comment = new \vc\Data\Comment;
        $prop = new \vc\Data\Property(123, $comment);

        $this->assertSame( 123, $prop->getLine() );
        $this->assertSame( $comment, $prop->getComment() );
    }

    public function testNameAccess ()
    {
        $prop = new \vc\Data\Property(123);

        $this->assertNull( $prop->getName() );
        $this->assertSame( $prop, $prop->setName("prop") );
        $this->assertSame( "prop", $prop->getName() );
    }

    public function testVisibilityAccess ()
    {
        $prop = new \vc\Data\Property(123);
        $this->assertEnum(\vc\Data\Visibility::vPUBLIC, $prop->getVisibility());

        $visibility = \vc\Data\Visibility::vPrivate();
        $this->assertSame( $prop, $prop->setVisibility($visibility) );
        $this->assertSame( $visibility, $prop->getVisibility() );
    }

    public function testStaticAccess ()
    {
        $prop = new \vc\Data\Property(123);
        $this->assertFalse( $prop->getStatic() );
        $this->assertSame( $prop, $prop->setStatic(TRUE) );
        $this->assertTrue( $prop->getStatic() );
    }

    public function testDefaultValueAccess ()
    {
        $prop = new \vc\Data\Property(123, new \vc\Data\Comment);
        $this->assertNull( $prop->getValue() );

        $value = new \vc\Data\Value('val', 'string');
        $this->assertSame( $prop, $prop->setValue($value) );
        $this->assertSame( $value, $prop->getValue() );
    }

}

