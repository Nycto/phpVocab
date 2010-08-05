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
class test_classes_Parser_Routine_Body extends \vc\Test\TestCase
{

    /**
     * Returns a Routine parser
     *
     * @return \vc\Parser\Routine\Body
     */
    public function getFuncParser ()
    {
        return new \vc\Parser\Routine\Body(
            new \vc\Parser\Routine\Args(
                new \vc\Parser\Path,
                new \vc\Parser\Value(
                    new \vc\Parser\Brackets
                )
            ),
            new \vc\Parser\Brackets
        );
    }

    public function testParseReference_WithoutArgs ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAFunction()
                ->thenSomeSpace()->thenAName('MyFunc')
                ->thenOpenParens()->thenCloseParens()
                ->thenAnOpenBlock()->thenACloseBlock()
        );

        $routine = $this->getMockForAbstractClass('\vc\Data\Routine', array(1));

        $this->getFuncParser()->parseRoutine( $routine, $access );

        $this->assertEquals('MyFunc', $routine->getName());
        $this->assertEndOfTokens( $access );
    }

    public function testParseReference_WithArgs ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAFunction()
                ->thenSomeSpace()->thenAName('MyFunc')
                ->thenOpenParens()->thenAVariable('$var')
                ->thenCloseParens()->thenAnOpenBlock()
                ->thenAnEcho()->thenSomeSpace()->thenAString('test')
                ->thenASemicolon()->thenACloseBlock()
        );

        $routine = $this->getMockForAbstractClass('\vc\Data\Routine', array(1));

        $this->getFuncParser()->parseRoutine( $routine, $access );

        $this->assertEquals('MyFunc', $routine->getName());
        $this->assertEquals(
            array( r8(new \vc\Data\Arg)->setVariable('$var') ),
            $routine->getArgs()
        );
        $this->assertEndOfTokens( $access );
    }

    public function testParseRoutine_ReturnReference ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAFunction()
                ->thenSomeSpace()->thenAnAmpersand()
                ->thenAName('MyFunc')
                ->thenOpenParens()->thenCloseParens()
                ->thenAnOpenBlock()->thenACloseBlock()
        );

        $routine = $this->getMockForAbstractClass('\vc\Data\Routine', array(1));

        $this->getFuncParser()->parseRoutine( $routine, $access );

        $this->assertEquals('MyFunc', $routine->getName());
        $this->assertTrue( $routine->getReturnRef() );
        $this->assertEndOfTokens( $access );
    }

    public function testParseReference_EndsWithASemiColon ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAFunction()
                ->thenSomeSpace()->thenAName('MyFunc')
                ->thenOpenParens()->thenCloseParens()
                ->thenASemicolon()
        );

        $routine = $this->getMockForAbstractClass('\vc\Data\Routine', array(1));

        $this->getFuncParser()->parseRoutine( $routine, $access );

        $this->assertEquals('MyFunc', $routine->getName());
        $this->assertEndOfTokens( $access );
    }

}

?>