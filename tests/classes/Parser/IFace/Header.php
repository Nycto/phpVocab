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

require_once rtrim( __DIR__, "/" ) ."/../../../setup.php";

/**
 * Unit tests
 */
class test_classes_Parser_IFace_Header extends \vc\Test\TestCase
{

    /**
     * Returns an interface header parser
     *
     * @return \vc\Parser\IFace\Header
     */
    public function getIFaceParser ()
    {
        $members = $this->getStub('\vc\Parser\IFace\Members');
        $members->expects( $this->once() )->method( "parseMembers" )
            ->with(
                $this->isInstanceOf('\vc\Data\Type\IFace'),
                $this->isInstanceOf('\vc\Tokens\Access')
            );

        return new \vc\Parser\IFace\Header(
            new \vc\Parser\PathList( new \vc\Parser\Path ),
            $members
        );
    }

    public function testParseIFace_WithoutExtends ()
    {
        $access = $this->getAccessParserWithComment(
            new \vc\Data\Comment('Data'),
            $this->oneTokenReader()->thenAnInterface(25)
                ->thenSomeSpace->thenAName('MyIFace')
                ->thenSomeSpace->thenAnOpenBlock
                ->thenACloseBlock
        );

        $this->assertEquals(
            r8(new \vc\Data\Type\IFace(25, new \vc\Data\Comment('Data')))
                ->setName('MyIFace'),
            $this->getIFaceParser()->parseIFace( $access )
        );

        $this->assertHasToken( Token::T_BLOCK_CLOSE, $access );
    }

    public function testParseIFace_WithExtends ()
    {
        $access = $this->getAccessParserWithComment(
            new \vc\Data\Comment('Data'),
            $this->oneTokenReader()->thenAnInterface(25)
                ->thenSomeSpace->thenAName('MyIFace')
                ->thenSomeSpace->thenAnExtends
                ->thenSomeSpace->thenAPathList(array('\parent', 'relative'))
                ->thenSomeSpace->thenAnOpenBlock
                ->thenACloseBlock
        );

        $this->assertEquals(
            r8(new \vc\Data\Type\IFace(25, new \vc\Data\Comment('Data')))
                ->setName('MyIFace')->setExtends(array('\parent', 'relative')),
            $this->getIFaceParser()->parseIFace( $access )
        );

        $this->assertHasToken( Token::T_BLOCK_CLOSE, $access );
    }

}

?>