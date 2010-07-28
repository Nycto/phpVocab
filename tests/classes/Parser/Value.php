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

    public function testParseValue_Integer ()
    {
        $access = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenSomeSpace()->thenAnInteger('1')
        );

        $parser = new \vc\Parser\Value;

        $this->assertEquals(
            new \vc\Data\Value('1', 'int'),
            $parser->parseValue( $access )
        );
    }


    public function testParseValue_Float ()
    {
        $access = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenSomeSpace()->thenAFloat('1.23')
        );

        $parser = new \vc\Parser\Value;

        $this->assertEquals(
            new \vc\Data\Value('1.23', 'float'),
            $parser->parseValue( $access )
        );
    }

    public function testParseValue_String ()
    {
        $access = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenSomeSpace()->thenAString('str')
        );

        $parser = new \vc\Parser\Value;

        $this->assertEquals(
            new \vc\Data\Value('str', 'string'),
            $parser->parseValue( $access )
        );
    }

    public function testParseValue_True ()
    {
        $access = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenSomeSpace()->thenAName('true')
        );

        $parser = new \vc\Parser\Value;

        $this->assertEquals(
            new \vc\Data\Value('true', 'bool'),
            $parser->parseValue( $access )
        );
    }

    public function testParseValue_False ()
    {
        $access = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenSomeSpace()->thenAName('false')
        );

        $parser = new \vc\Parser\Value;

        $this->assertEquals(
            new \vc\Data\Value('false', 'bool'),
            $parser->parseValue( $access )
        );
    }

    public function testParseValue_Null ()
    {
        $access = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenSomeSpace()->thenAName('null')
        );

        $parser = new \vc\Parser\Value;

        $this->assertEquals(
            new \vc\Data\Value('null', 'null'),
            $parser->parseValue( $access )
        );
    }

    public function testParseValue_InvalidName ()
    {
        $access = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenSomeSpace()->thenAName('other')
        );

        $parser = new \vc\Parser\Value;

        try {
            $parser->parseValue( $access );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testParseValue_HereDoc ()
    {
        $access = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenSomeSpace()->thenAHereDoc('str')
        );

        $parser = new \vc\Parser\Value;

        $this->assertEquals(
            new \vc\Data\Value('str', 'string'),
            $parser->parseValue( $access )
        );
    }

    public function testParseValue_InvalidToken ()
    {
        $access = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenAClass()
        );

        $parser = new \vc\Parser\Value;

        try {
            $parser->parseValue( $access );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\UnexpectedToken $err ) {}
    }

    public function testParseValue_WithEquals ()
    {
        $access = new \vc\Tokens\Search(
            $this->oneTokenReader()
                ->thenSomeSpace()->thenAnEquals()->thenSomeSpace()
                ->thenAnInteger('1')
        );

        $parser = new \vc\Parser\Value;

        $this->assertEquals(
            new \vc\Data\Value('1', 'int'),
            $parser->parseValue( $access )
        );
    }

}

?>