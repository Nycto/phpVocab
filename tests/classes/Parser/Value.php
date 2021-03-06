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

use \vc\Tokens\Token as Token;

/**
 * Unit tests
 */
class test_classes_Parser_Value extends \vc\Test\TestCase
{

    /**
     * Returns a Value parser
     *
     * @return \vc\Parser\Value
     */
    public function getValueParser ()
    {
        return new \vc\Parser\Value(
            new \vc\Parser\Brackets,
            new \vc\Parser\Path
        );
    }

    public function testParseValue_PositiveInteger ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace->thenAnInteger('1')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('1', 'int'),
            $parser->parseValue( $access )
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseValue_NegiativeInteger ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace
                ->then(Token::T_MINUS, '-')->thenAnInteger('1')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('-1', 'int'),
            $parser->parseValue( $access )
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseValue_PositiveFloat ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace->thenAFloat('1.23')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('1.23', 'float'),
            $parser->parseValue( $access )
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseValue_NegativeFloat ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace
                ->then(Token::T_MINUS, '-')->thenAFloat('1.23')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('-1.23', 'float'),
            $parser->parseValue( $access )
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseValue_String ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace->thenAString('str')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('str', 'string'),
            $parser->parseValue( $access )
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseValue_True ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace->thenAName('true')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('true', 'bool'),
            $parser->parseValue( $access )
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseValue_False ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace->thenAName('false')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('false', 'bool'),
            $parser->parseValue( $access )
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseValue_Null ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace->thenAName('null')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('null', 'null'),
            $parser->parseValue( $access )
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseValue_HereDoc ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenSomeSpace->thenAHereDoc('str')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('str', 'string'),
            $parser->parseValue( $access )
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseValue_Array ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAnArrayValue(array(1,2))
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('array( 0 => 1, 1 => 2,)', 'array'),
            $parser->parseValue( $access )
        );

        $this->assertEndOfTokens($access);
    }

    public function testParseValue_InvalidToken ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAClass
        );

        $parser = $this->getValueParser();

        try {
            $parser->parseValue( $access );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\Exception\UnexpectedToken $err ) {}
    }

    public function testParseValue_WithEquals ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenSomeSpace->thenAnEquals->thenSomeSpace
                ->thenAnInteger('1')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('1', 'int'),
            $parser->parseValue( $access )
        );
        $this->assertEndOfTokens($access);
    }

    public function testParseValue_RelativeConstant ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenSomeSpace->thenAnEquals->thenSomeSpace
                ->thenANamespacePath('relative\path')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('relative\path', 'constant'),
            $parser->parseValue( $access )
        );
        $this->assertEndOfTokens($access);
    }

    public function testParseValue_AbsoluteConstant ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenSomeSpace->thenAnEquals->thenSomeSpace
                ->thenANamespacePath('\abs\path')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('\abs\path', 'constant'),
            $parser->parseValue( $access )
        );
        $this->assertEndOfTokens($access);
    }

    public function testParseValue_ClassConstant ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenSomeSpace->thenAnEquals->thenSomeSpace
                ->thenANamespacePath('\path')
                ->then( Token::T_DOUBLE_COLON, '::' )
                ->thenAName('CONSTANT')
        );

        $parser = $this->getValueParser();

        $this->assertEquals(
            new \vc\Data\Value('\path::CONSTANT', 'constant'),
            $parser->parseValue( $access )
        );
        $this->assertEndOfTokens($access);
    }

}

