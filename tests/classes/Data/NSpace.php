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
class test_classes_Data_NSpace extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $nspace = new \vc\Data\NSpace('path');
        $this->assertSame( 'path', $nspace->getPath() );
    }

    public function testConstantAccess ()
    {
        $nspace = new \vc\Data\NSpace('path');
        $this->assertSame( array(), $nspace->getConstants() );

        $const1 = new \vc\Data\Constant("CONST", "value");
        $this->assertSame( $nspace, $nspace->addConstant($const1) );
        $this->assertSame( array($const1), $nspace->getConstants() );

        $const2 = new \vc\Data\Constant("CONST", "value");
        $this->assertSame( $nspace, $nspace->addConstant($const2) );
        $this->assertSame( array($const1, $const2), $nspace->getConstants() );
    }

    public function testFunctionAccess ()
    {
        $nspace = new \vc\Data\NSpace('path');
        $this->assertSame( array(), $nspace->getFunctions() );

        $func1 = new \vc\Data\Routine\Func(123);
        $this->assertSame( $nspace, $nspace->addFunction($func1) );
        $this->assertSame( array($func1), $nspace->getFunctions() );

        $func2 = new \vc\Data\Routine\Func(123);
        $this->assertSame( $nspace, $nspace->addFunction($func2) );
        $this->assertSame( array($func1, $func2), $nspace->getFunctions() );
    }

    public function testTypeAccess ()
    {
        $nspace = new \vc\Data\NSpace('path');
        $this->assertSame( array(), $nspace->getTypes() );

        $type1 = $this->getMockForAbstractClass('\vc\Data\Type', array(1));
        $this->assertSame( $nspace, $nspace->addType($type1) );
        $this->assertSame( array($type1), $nspace->getTypes() );

        $type2 = $this->getMockForAbstractClass('\vc\Data\Type', array(1));
        $this->assertSame( $nspace, $nspace->addType($type2) );
        $this->assertSame( array($type1, $type2), $nspace->getTypes() );
    }

}

?>