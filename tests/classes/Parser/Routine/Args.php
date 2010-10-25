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
class test_classes_Parser_Routine_Args extends \vc\Test\TestCase
{

    /**
     * Returns am Argument Parser
     *
     * @return \vc\Parser\Routine\Args
     */
    public function getArgParser ()
    {
        return new \vc\Parser\Routine\Args(
            new \vc\Parser\Path,
            new \vc\Parser\Value(
                new \vc\Parser\Brackets,
                new \vc\Parser\Path
            )
        );
    }

    public function testParseArgs_NoArguments ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenOpenParens->thenSomeSpace
                ->thenCloseParens
        );

        $this->assertSame( array(), $this->getArgParser()->parseArgs($access) );

        $this->assertEndOfTokens($access);
    }

    public function testParseArgs_BasicArgument ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenOpenParens
                ->thenAVariable('$test')->thenCloseParens
        );

        $this->assertEquals(
            array( \r8(new \vc\Data\Arg)->setVariable('$test') ),
            $this->getArgParser()->parseArgs($access)
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseArgs_MultipleArguments ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenOpenParens
                ->thenAVariable('$test')->thenAComma
                ->thenAVariable('$test2')->thenAComma
                ->thenAVariable('$test3')
                ->thenCloseParens
        );

        $this->assertEquals(
            array(
                \r8(new \vc\Data\Arg)->setVariable('$test'),
                \r8(new \vc\Data\Arg)->setVariable('$test2'),
                \r8(new \vc\Data\Arg)->setVariable('$test3')
            ),
            $this->getArgParser()->parseArgs($access)
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseArgs_AbsoluteClassTypeHinting ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenOpenParens
                ->thenANamespacePath('\test\path')->thenSomeSpace
                ->thenAVariable('$test')
                ->thenCloseParens
        );

        $this->assertEquals(
            array(
                \r8(new \vc\Data\Arg)
                    ->setVariable('$test')
                    ->setType('\test\path')
            ),
            $this->getArgParser()->parseArgs($access)
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseArgs_RelativeClassTypeHinting ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenOpenParens
                ->thenANamespacePath('test\path')->thenSomeSpace
                ->thenAVariable('$test')
                ->thenCloseParens
        );

        $this->assertEquals(
            array(
                \r8(new \vc\Data\Arg)
                    ->setVariable('$test')
                    ->setType('test\path')
            ),
            $this->getArgParser()->parseArgs($access)
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseArgs_ArrayTypeHinting ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenOpenParens
                ->thenAnArray('array')->thenSomeSpace
                ->thenAVariable('$test')
                ->thenCloseParens
        );

        $this->assertEquals(
            array(
                \r8(new \vc\Data\Arg)->setVariable('$test')->setType('array')
            ),
            $this->getArgParser()->parseArgs($access)
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseArgs_PassByReferenceValue ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenOpenParens
                ->thenAnAmpersand
                ->thenAVariable('$test')
                ->thenCloseParens
        );

        $this->assertEquals(
            array(
                \r8(new \vc\Data\Arg)->setReference(TRUE)->setVariable('$test')
            ),
            $this->getArgParser()->parseArgs($access)
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseArgs_DefaultValue ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenOpenParens
                ->thenAVariable('$test')
                ->thenSomeSpace->thenAnEquals
                ->thenSomeSpace->thenAnInteger(123)
                ->thenCloseParens
        );

        $this->assertEquals(
            array(
                \r8(new \vc\Data\Arg)
                    ->setVariable('$test')
                    ->setDefault( new \vc\Data\Value(123, 'int') )
            ),
            $this->getArgParser()->parseArgs($access)
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseArgs_Combined ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenOpenParens

                ->thenANamespacePath('\path\to\class')->thenSomeSpace
                ->thenAnAmpersand->thenSomeSpace
                ->thenAVariable('$test')
                ->thenSomeSpace->thenAnEquals
                ->thenSomeSpace->thenAnInteger(123)
                ->thenAComma

                ->thenAnArray->thenSomeSpace
                ->thenAnAmpersand->thenSomeSpace
                ->thenAVariable('$test2')
                ->thenSomeSpace->thenAnEquals
                ->thenSomeSpace->thenAnArrayValue(array(1))

                ->thenCloseParens
        );

        $this->assertEquals(
            array(
                \r8(new \vc\Data\Arg)
                    ->setType('\path\to\class')
                    ->setReference(TRUE)
                    ->setVariable('$test')
                    ->setDefault( new \vc\Data\Value(123, 'int') ),

                \r8(new \vc\Data\Arg)
                    ->setType('array')
                    ->setReference(TRUE)
                    ->setVariable('$test2')
                    ->setDefault(new \vc\Data\Value('array( 0 => 1,)', 'array'))
            ),
            $this->getArgParser()->parseArgs($access)
        );

        $this->assertEndOfTokens($access);
    }

}

