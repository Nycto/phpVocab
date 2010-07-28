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
 * Unit test
 */
class test_classes_Data_Value extends \vc\Test\TestCase
{

    public function testConstruct ()
    {
        $value = new \vc\Data\Value('val', 'string');
        $this->assertSame( 'val', $value->getValue() );
        $this->assertSame( 'string', $value->getType() );
    }

    public function testBuildFromToken_String ()
    {
        $this->assertEquals(
            new \vc\Data\Value('some data', 'string'),
            \vc\Data\Value::buildFromToken(
                new \vc\Tokens\Token(
                    Token::T_CONSTANT_ENCAPSED_STRING,
                    "'some data'",
                    1
                )
            )
        );
    }

    public function testBuildFromToken_HereDocString ()
    {
        $this->assertEquals(
            new \vc\Data\Value('some data', 'string'),
            \vc\Data\Value::buildFromToken(
                new \vc\Tokens\Token(
                    Token::T_ENCAPSED_AND_WHITESPACE,
                    "some data",
                    1
                )
            )
        );
    }

    public function testBuildFromToken_Integer ()
    {
        $this->assertEquals(
            new \vc\Data\Value('123', 'int'),
            \vc\Data\Value::buildFromToken(
                new \vc\Tokens\Token( Token::T_LNUMBER, "123", 1 )
            )
        );
    }

    public function testBuildFromToken_Float ()
    {
        $this->assertEquals(
            new \vc\Data\Value('3.14', 'float'),
            \vc\Data\Value::buildFromToken(
                new \vc\Tokens\Token( Token::T_DNUMBER, "3.14", 1 )
            )
        );
    }

    public function testBuildFromToken_NULL ()
    {
        $this->assertEquals(
            new \vc\Data\Value('NULL', 'null'),
            \vc\Data\Value::buildFromToken(
                new \vc\Tokens\Token( Token::T_STRING, "NULL", 1 )
            )
        );
    }

    public function testBuildFromToken_True ()
    {
        $this->assertEquals(
            new \vc\Data\Value('True', 'bool'),
            \vc\Data\Value::buildFromToken(
                new \vc\Tokens\Token( Token::T_STRING, "True", 1 )
            )
        );
    }

    public function testBuildFromToken_False ()
    {
        $this->assertEquals(
            new \vc\Data\Value('FALSE', 'bool'),
            \vc\Data\Value::buildFromToken(
                new \vc\Tokens\Token( Token::T_STRING, "FALSE", 1 )
            )
        );
    }

    public function testBuildFromToken_UnknownStringType ()
    {
        try {
            \vc\Data\Value::buildFromToken(
                new \vc\Tokens\Token( Token::T_STRING, "unknown", 1 )
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {}
    }

    public function testBuildFromToken_UnknownToken ()
    {
        try {
            \vc\Data\Value::buildFromToken(
                new \vc\Tokens\Token( Token::T_CLASS, "class", 1 )
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\UnexpectedToken $err ) {}
    }

}

?>