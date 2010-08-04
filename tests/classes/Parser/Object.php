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

use \vc\Tokens\Token as Token;

require_once rtrim( __DIR__, "/" ) ."/../../setup.php";

/**
 * Unit tests
 */
class test_classes_Parser_Object extends \vc\Test\TestCase
{

    /**
     * Returns an object parser
     *
     * @return \vc\Parser\Object
     */
    public function getObjectParser ()
    {
        $members = $this->getStub('\vc\Parser\Object\Members');
        $members->expects( $this->once() )->method( "parseMembers" )
            ->with(
                $this->isInstanceOf('\vc\Data\Type\Cls'),
                $this->isInstanceOf('\vc\Tokens\Access')
            );

        return new \vc\Parser\Object(
            new \vc\Parser\Path,
            new \vc\Parser\PathList(
                new \vc\Parser\Path
            ),
            $members
        );
    }

    public function testParseClass_BasicClass ()
    {
        $access = $this->getAccessParserWithComment(
            new \vc\Data\Comment('Data'),
            $this->oneTokenReader()->thenAClass(25)
                ->thenSomeSpace()->thenAName('MyClass')
                ->thenSomeSpace()->thenAnOpenBlock()
                ->thenACloseBlock()
        );

        $this->assertEquals(
            r8(new \vc\Data\Type\Cls(25, new \vc\Data\Comment('Data')))
                ->setName('MyClass'),
            $this->getObjectParser()->parseClass( $access )
        );
    }

    public function testParseClass_AbstractClass ()
    {
        $access = $this->getAccessParserWithComment(
            new \vc\Data\Comment('Data'),
            $this->oneTokenReader()->thenAnAbstract(25)
                ->thenSomeSpace()->thenAClass()
                ->thenSomeSpace()->thenAName('MyClass')
                ->thenSomeSpace()->thenAnOpenBlock()
                ->thenACloseBlock()
        );

        $this->assertEquals(
            r8(new \vc\Data\Type\Cls(25, new \vc\Data\Comment('Data')))
                ->setAbstract(TRUE)->setName('MyClass'),
            $this->getObjectParser()->parseClass( $access )
        );
    }

    public function testParseClass_Extends ()
    {
        $access = $this->getAccessParserWithComment(
            new \vc\Data\Comment('Data'),
            $this->oneTokenReader()
                ->thenSomeSpace()->thenAClass()
                ->thenSomeSpace()->thenAName('MyClass')
                ->thenSomeSpace()->thenAnExtends('\path\to\parent')
                ->thenSomeSpace()->thenAnOpenBlock()->thenACloseBlock()
        );

        $this->assertEquals(
            r8(new \vc\Data\Type\Cls(1, new \vc\Data\Comment('Data')))
                ->setName('MyClass')->setExtends('\path\to\parent'),
            $this->getObjectParser()->parseClass( $access )
        );
    }

    public function testParseClass_Implements ()
    {
        $access = $this->getAccessParserWithComment(
            new \vc\Data\Comment('Data'),
            $this->oneTokenReader()
                ->thenSomeSpace()->thenAClass()->thenSomeSpace()
                ->thenAName('MyClass')->thenSomeSpace()
                ->thenAnImplements(array('one', '\path\two'))
                ->thenSomeSpace()->thenAnOpenBlock()->thenACloseBlock()
        );

        $this->assertEquals(
            r8(new \vc\Data\Type\Cls(1, new \vc\Data\Comment('Data')))
                ->setName('MyClass')
                ->setIFaces(array('one', '\path\two')),
            $this->getObjectParser()->parseClass( $access )
        );
    }

    public function testParseClass_Full ()
    {
        $access = $this->getAccessParserWithComment(
            new \vc\Data\Comment('Data'),
            $this->oneTokenReader()->thenAnAbstract(123)
                ->thenSomeSpace()->thenAClass()->thenSomeSpace()
                ->thenAName('MyClass')->thenSomeSpace()
                ->thenAnExtends('parent')->thenSomeSpace()
                ->thenAnImplements(array('one', '\path\two'))
                ->thenSomeSpace()->thenAnOpenBlock()->thenACloseBlock()
        );

        $this->assertEquals(
            r8(new \vc\Data\Type\Cls(123, new \vc\Data\Comment('Data')))
                ->setName('MyClass')->setAbstract(TRUE)->setExtends('parent')
                ->setIFaces(array('one', '\path\two')),
            $this->getObjectParser()->parseClass( $access )
        );
    }

}

?>
