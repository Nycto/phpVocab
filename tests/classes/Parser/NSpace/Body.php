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
 * Unit tests
 */
class test_classes_Parser_NSpace_Body extends \vc\Test\TestCase
{

    /**
     * Returns a test Namespace body parser
     *
     * @return \vc\Parser\NSpace\Body
     */
    public function getNSpaceParser ()
    {
        $constant = $this->getStub('\vc\Parser\Constant');
        $constant->expects( $this->any() )->method( "parseConstant" )
            ->will( $this->returnCallback( function ( $access ) {
                $access->popToken();
                return new \vc\Data\Constant('CONST');
            }) );

        $func = $this->getStub('\vc\Parser\Routine\Func');
        $func->expects( $this->any() )->method( "parseFunc" )
            ->will( $this->returnCallback( function ( $access ) {
                $access->popToken();
                return new \vc\Data\Routine\Func(1);
            }) );

        $cls = $this->getStub('\vc\Parser\Object\Header');
        $cls->expects( $this->any() )->method( "parseClass" )
            ->will( $this->returnCallback( function ( $access ) {
                $access->popToken();
                return new \vc\Data\Type\Cls(1);
            }) );

        $iface = $this->getStub('\vc\Parser\IFace\Header');
        $iface->expects( $this->any() )->method( "parseIFace" )
            ->will( $this->returnCallback( function ( $access ) {
                $access->popToken();
                return new \vc\Data\Type\IFace(1);
            }) );

        return new \vc\Parser\NSpace\Body(
            $constant, $func, $cls, $iface
        );
    }

    public function testParseNSpace_Constant ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAConst
        );

        $nspace = new \vc\Data\NSpace(1);
        $this->getNSpaceParser()->parseNSpace( $nspace, $access );

        $this->assertEquals(1, count($nspace->getConstants()));
        $this->assertEndOfTokens( $access );
    }

    public function testParseNSpace_Abstract ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAnAbstract
        );

        $nspace = new \vc\Data\NSpace(1);
        $this->getNSpaceParser()->parseNSpace( $nspace, $access );

        $this->assertEquals(1, count($nspace->getTypes()));
        $this->assertEndOfTokens( $access );
    }

    public function testParseNSpace_Class ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAClass
        );

        $nspace = new \vc\Data\NSpace(1);
        $this->getNSpaceParser()->parseNSpace( $nspace, $access );

        $this->assertEquals(1, count($nspace->getTypes()));
        $this->assertEndOfTokens( $access );
    }

    public function testParseNSpace_Function ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAFunction
        );

        $nspace = new \vc\Data\NSpace(1);
        $this->getNSpaceParser()->parseNSpace( $nspace, $access );

        $this->assertEquals(1, count($nspace->getFunctions()));
        $this->assertEndOfTokens( $access );
    }

    public function testParseNSpace_Interfaces ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAnInterface
        );

        $nspace = new \vc\Data\NSpace(1);
        $this->getNSpaceParser()->parseNSpace( $nspace, $access );

        $this->assertEquals(1, count($nspace->getTypes()));
        $this->assertEndOfTokens( $access );
    }

    public function testParseNSpace_Many ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAnInterface->thenAClass
                ->thenAConst->thenAFunction
        );

        $nspace = new \vc\Data\NSpace(1);
        $this->getNSpaceParser()->parseNSpace( $nspace, $access );

        $this->assertEquals(2, count($nspace->getTypes()));
        $this->assertEquals(1, count($nspace->getConstants()));
        $this->assertEquals(1, count($nspace->getFunctions()));
        $this->assertEndOfTokens( $access );
    }

}

?>