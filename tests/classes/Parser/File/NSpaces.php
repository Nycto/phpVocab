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

use \vc\Tokens\Token as Token;

/**
 * Unit test
 */
class test_classes_Parser_File_NSpaces extends \vc\Test\TestCase
{

    /**
     * Returns a namespace parser that will consume a single token
     *
     * @return \vc\Parser\NSpace
     */
    public function getNSpaceParser ( $count = 1 )
    {
        $inner = $this->getStub('\vc\Parser\NSpace\Body');
        $inner->expects( $this->exactly($count) )->method( "parseNSpace" )
            ->will($this->returnCallback(function ( $nspace, $access ) {
                $access->popToken();
            }));
        return $inner;
    }

    public function testParse_OneGlobalNamespace ()
    {
        $file = new \vc\Data\File('path.php');

        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAFunction
        );

        $parser = new \vc\Parser\File\NSpaces(
            new \vc\Parser\Path, $this->getNSpaceParser()
        );
        $parser->parse( $file, $access );


        $this->assertEquals(
            array( new \vc\Data\NSpace ),
            $file->getNamespaces()
        );

        $this->assertEndOfTokens( $access );
    }

    public function testParse_SemicolonNamespaceDefinition ()
    {
        $file = new \vc\Data\File('path.php');

        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenANamespace('sub\\sub2')->thenASemicolon
                ->thenAFunction
        );

        $parser = new \vc\Parser\File\NSpaces(
            new \vc\Parser\Path, $this->getNSpaceParser()
        );
        $parser->parse( $file, $access );


        $this->assertEquals(
            array( new \vc\Data\NSpace('sub\\sub2') ),
            $file->getNamespaces()
        );

        $this->assertEndOfTokens( $access );
    }

    public function testParse_curlyNamespaceDefinition ()
    {
        $file = new \vc\Data\File('path.php');

        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenANamespace('sub\\sub2')->thenSomeSpace->thenAnOpenBlock
                ->thenAFunction
        );

        $parser = new \vc\Parser\File\NSpaces(
            new \vc\Parser\Path, $this->getNSpaceParser()
        );
        $parser->parse( $file, $access );


        $this->assertEquals(
            array( new \vc\Data\NSpace('sub\\sub2') ),
            $file->getNamespaces()
        );

        $this->assertEndOfTokens( $access );
    }

    public function testParse_MultipleCurlyNamespaceDefinitions ()
    {
        $file = new \vc\Data\File('path.php');

        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenANamespace('sub\\sub2')->thenSomeSpace->thenAnOpenBlock
                ->thenAFunction
                ->thenANamespace('ns2')->thenSomeSpace->thenAnOpenBlock
                ->thenAFunction
        );

        $parser = new \vc\Parser\File\NSpaces(
            new \vc\Parser\Path, $this->getNSpaceParser(2)
        );
        $parser->parse( $file, $access );


        $this->assertEquals(
            array(
                new \vc\Data\NSpace('sub\\sub2'),
                new \vc\Data\NSpace('ns2')
            ),
            $file->getNamespaces()
        );

        $this->assertEndOfTokens( $access );
    }

    public function testParse_MultipleSemicolonNamespaceDefinitions ()
    {
        $file = new \vc\Data\File('path.php');

        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenANamespace('sub\\sub2')->thenASemicolon
                ->thenAFunction
                ->thenANamespace('ns2')->thenASemicolon
                ->thenAFunction
        );

        $parser = new \vc\Parser\File\NSpaces(
            new \vc\Parser\Path, $this->getNSpaceParser(2)
        );
        $parser->parse( $file, $access );


        $this->assertEquals(
            array(
                new \vc\Data\NSpace('sub\\sub2'),
                new \vc\Data\NSpace('ns2')
            ),
            $file->getNamespaces()
        );

        $this->assertEndOfTokens( $access );
    }

}

