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
class test_classes_Parser_PathList extends \vc\Test\TestCase
{

    /**
     * Returns a path list parser
     *
     * @return \vc\Parser\PathList
     */
    public function getPathListParser ()
    {
        return new \vc\Parser\PathList(
            new \vc\Parser\Path
        );
    }

    public function testParsePathList_NoPaths ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace->thenAnOpenBlock
        );

        try {
            $this->getPathListParser()->parsePathList( $access );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\Exception\UnexpectedToken $err ) {}
    }

    public function testParsePathList_OnePath ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace
                ->thenANamespacePath('path\to\cls')->thenSomeSpace
                ->thenAnOpenBlock
        );

        $this->assertEquals(
            array('path\to\cls'),
            $this->getPathListParser()->parsePathList( $access )
        );
    }

    public function testParsePathList_TwoPaths ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace
                ->thenANamespacePath('path\to\cls')->thenAComma->thenSomeSpace
                ->thenANamespacePath('\stdClass')->thenSomeSpace
                ->thenAnOpenBlock
        );

        $this->assertEquals(
            array('path\to\cls', '\stdClass'),
            $this->getPathListParser()->parsePathList( $access )
        );
    }

    public function testParsePathList_ThreePaths ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace
                ->thenANamespacePath('path\to\cls')->thenAComma->thenSomeSpace
                ->thenANamespacePath('AnObject')->thenAComma->thenSomeSpace
                ->thenANamespacePath('\stdClass')->thenSomeSpace
                ->thenAnOpenBlock
        );

        $this->assertEquals(
            array('path\to\cls', 'AnObject', '\stdClass'),
            $this->getPathListParser()->parsePathList( $access )
        );
    }

}

?>