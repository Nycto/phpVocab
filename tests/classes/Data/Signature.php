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
class test_classes_Data_Signature extends \vc\Test\TestCase
{

    public function testConstruct ()
    {
        $comment = new \vc\Data\Comment;
        $sig = new \vc\Data\Signature( 123, $comment );
        $this->assertSame( 123, $sig->getLine() );
        $this->assertSame( $comment, $sig->getComment() );


        $sig = new \vc\Data\Signature( 123 );
        $this->assertSame( 123, $sig->getLine() );
        $this->assertEquals( new \vc\Data\Comment, $sig->getComment() );
    }

    public function testVisibilityAccess ()
    {
        $sig = new \vc\Data\Signature( 123, new \vc\Data\Comment );
        $this->assertEnum(\vc\Data\Visibility::vPUBLIC, $sig->getVisibility() );

        $visibility = \vc\Data\Visibility::vPrivate();
        $this->assertSame( $sig, $sig->setVisibility($visibility) );
        $this->assertSame( $visibility, $sig->getVisibility() );
    }

    public function testStaticAccess ()
    {
        $sig = new \vc\Data\Signature( 123, new \vc\Data\Comment );
        $this->assertFalse( $sig->getStatic() );
        $this->assertSame( $sig, $sig->setStatic(TRUE) );
        $this->assertTrue( $sig->getStatic() );
    }

    public function testBuildMethod ()
    {
        $comment = new \vc\Data\Comment;
        $sig = new \vc\Data\Signature( 123, $comment );
        $sig->setVisibility( \vc\Data\Visibility::vPrivate() );
        $sig->setStatic( TRUE );

        $meth = $sig->buildMethod();
        $this->assertSame( 123, $meth->getLine() );
        $this->assertSame( $comment, $meth->getComment() );
        $this->assertTrue( $meth->getStatic() );
        $this->assertEnum(\vc\Data\Visibility::vPRIVATE, $meth->getVisibility());
    }

    public function testBuildProperty ()
    {
        $comment = new \vc\Data\Comment;
        $sig = new \vc\Data\Signature( 123, $comment );
        $sig->setVisibility( \vc\Data\Visibility::vPrivate() );
        $sig->setStatic( TRUE );

        $prop = $sig->buildProperty();
        $this->assertSame( 123, $prop->getLine() );
        $this->assertSame( $comment, $prop->getComment() );
        $this->assertTrue( $prop->getStatic() );
        $this->assertEnum(\vc\Data\Visibility::vPRIVATE, $prop->getVisibility());
    }

}

