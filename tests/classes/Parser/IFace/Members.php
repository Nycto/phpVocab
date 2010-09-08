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
 * Unit tests
 */
class test_classes_Parser_IFace_Members extends \vc\Test\TestCase
{

    /**
     * Asserts that a reader will add a method to an interface
     *
     * @return NULL
     */
    public function assertAddsAMethod ( \vc\iface\Tokens\Reader $reader )
    {
        $constParser = $this->getStub('\vc\Parser\Constant');
        $constParser->expects( $this->never() )->method( "parseConstant" );

        $methParser = $this->getStub('\vc\Parser\Routine\Method');
        $methParser->expects( $this->once() )->method( "parseMethod" )
            ->will( $this->returnCallback( function ( $signature, $access ) {
                $access->popToken();
                return new \vc\Data\Routine\Method(1);
            } ) );

        $parser = new \vc\Parser\IFace\Members( $constParser, $methParser );

        $iface = new \vc\Data\Type\IFace(1);
        $parser->parseMembers($iface, \vc\Tokens\Access::buildAccess($reader));

        $this->assertEquals( 0, count($iface->getConstants()) );
        $this->assertEquals( 1, count($iface->getMethods()) );
        $this->assertEndOfTokens( $reader );
    }

    public function testParseIFace_EmptyInterface ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace
        );

        $iface = new \vc\Data\Type\IFace(1);

        $parser = new \vc\Parser\IFace\Members(
            new \vc\Parser\Constant(
                new \vc\Parser\Value(
                    new \vc\Parser\Brackets,
                    new \vc\Parser\Path
                )
            ),
            $this->getStub('\vc\Parser\Routine\Method')
        );

        $parser->parseMembers( $iface, $access );
        $this->assertEndOfTokens( $access );
    }

    public function testParseIFace_Constant ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenAConst->thenAName('CONST')
                ->thenAnEquals->thenAnInteger(123)
                ->thenASemicolon
        );

        $iface = new \vc\Data\Type\IFace(1);

        $parser = new \vc\Parser\IFace\Members(
            new \vc\Parser\Constant(
                new \vc\Parser\Value(
                    new \vc\Parser\Brackets,
                    new \vc\Parser\Path
                )
            ),
            $this->getStub('\vc\Parser\Routine\Method')
        );

        $parser->parseMembers( $iface, $access );

        $this->assertEquals( 1, count($iface->getConstants()) );
        $this->assertEndOfTokens( $access );
    }

    public function testParseIFace_Static ()
    {
        $this->assertAddsAMethod( $this->oneTokenReader()->thenAStatic );
    }

    public function testParseIFace_Public ()
    {
        $this->assertAddsAMethod( $this->oneTokenReader()->thenAPublic );
    }

    public function testParseIFace_Private ()
    {
        $this->assertAddsAMethod( $this->oneTokenReader()->thenAPrivate );
    }

    public function testParseIFace_Protected ()
    {
        $this->assertAddsAMethod( $this->oneTokenReader()->thenAProtected );
    }

    public function testParseIFace_Function ()
    {
        $this->assertAddsAMethod( $this->oneTokenReader()->thenAFunction );
    }

}

?>