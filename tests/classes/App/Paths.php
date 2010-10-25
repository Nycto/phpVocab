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
class test_classes_App_Paths extends \r8\Test\TestCase\Dir
{

    public function testIterate_Empty ()
    {
        $input = new \vc\App\Paths;
        \r8\Test\Constraint\Iterator::assert(array(), $input);
    }

    public function testAddInput_Files ()
    {
        $input = new \vc\App\Paths;

        $this->assertSame(
            $input,
            $input->addInput(\r8\FileSys::create($this->dir .'/one'))
        );

        $this->assertSame(
            $input,
            $input->addInput(\r8\FileSys::create($this->dir .'/two'))
        );

        $value = \r8\Test\Constraint\Iterator::iteratorToArray(10, $input);

        $this->assertEquals( 2, count($value) );
        $this->assertEquals(array(0,1), array_keys($value));

        $this->assertSame( 'one', $value[0]->getBaseName() );
        $this->assertSame( 'two', $value[1]->getBaseName() );
    }

    public function testAddInput_Directories ()
    {
        $input = new \vc\App\Paths;

        $this->assertSame(
            $input,
            $input->addInput(\r8\FileSys::create($this->dir .'/third'))
        );

        $value = \r8\Test\Constraint\Iterator::iteratorToArray(10, $input);

        $this->assertEquals( 5, count($value) );
        $this->assertEquals(array(0,1,2,3,4), array_keys($value));

        $this->assertSame( 'third-one', $value[0]->getBaseName() );
        $this->assertSame( 'fourth-two', $value[1]->getBaseName() );
        $this->assertSame( 'fourth-one', $value[2]->getBaseName() );
        $this->assertSame( 'third-two', $value[3]->getBaseName() );
        $this->assertSame( 'third-three', $value[4]->getBaseName() );
    }

    public function testAddInput_NonFile ()
    {
        $input = new \vc\App\Paths;


        try {
            $input->addInput( \r8\FileSys::create($this->dir .'/NotAFile') );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {}
    }

}

