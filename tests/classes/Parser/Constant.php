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
class test_classes_Parser_Constant extends \vc\Test\TestCase
{

    public function testParseConstant ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenAName('CONSTANT')->thenSomeSpace()
                ->thenAnEquals()->thenSomeSpace()
                ->thenAnInteger(123)->thenASemicolon()
                ->thenAClass()
        );

        $parser = new \vc\Parser\Constant(
            new \vc\Parser\Value( new \vc\Parser\Brackets )
        );

        $this->assertEquals(
            r8(new \vc\Data\Constant('CONSTANT'))
                ->setValue(new \vc\Data\Value('123', 'int')),
            $parser->parseConstant( $access )
        );

        $this->assertHasToken( Token::T_CLASS, $access );
    }

    public function testParseConstant_FloatValue ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenAName('NAME')->thenSomeSpace()
                ->thenAnEquals()->thenSomeSpace()
                ->thenAFloat(12.3)->thenASemicolon()
                ->thenAClass()
        );

        $parser = new \vc\Parser\Constant(
            new \vc\Parser\Value( new \vc\Parser\Brackets )
        );

        $this->assertEquals(
            r8(new \vc\Data\Constant('NAME'))
                ->setValue(new \vc\Data\Value('12.3', 'float')),
            $parser->parseConstant( $access )
        );

        $this->assertHasToken( Token::T_CLASS, $access );
    }

    public function testParseConstant_StringWithSingleQuotes ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenAName('NAME')->thenSomeSpace()
                ->thenAnEquals()->thenSomeSpace()
                ->thenAString('string', "'")->thenASemicolon()
                ->thenAClass()
        );

        $parser = new \vc\Parser\Constant(
            new \vc\Parser\Value( new \vc\Parser\Brackets )
        );

        $this->assertEquals(
            r8(new \vc\Data\Constant('NAME'))
                ->setValue(new \vc\Data\Value('string', 'string')),
            $parser->parseConstant( $access )
        );

        $this->assertHasToken( Token::T_CLASS, $access );
    }

    public function testParseConstant_StringWithDoubleQuotes ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenAName('NAME')->thenSomeSpace()
                ->thenAnEquals()->thenSomeSpace()
                ->thenAString('string', '"')->thenASemicolon()
                ->thenAClass()
        );

        $parser = new \vc\Parser\Constant(
            new \vc\Parser\Value( new \vc\Parser\Brackets )
        );

        $this->assertEquals(
            r8(new \vc\Data\Constant('NAME'))
                ->setValue(new \vc\Data\Value('string', 'string')),
            $parser->parseConstant( $access )
        );

        $this->assertHasToken( Token::T_CLASS, $access );
    }

    public function testParseConstant_Null ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenAName('NAME')->thenSomeSpace()
                ->thenAnEquals()->thenSomeSpace()
                ->thenAName('NULL')->thenASemicolon()
                ->thenAClass()
        );

        $parser = new \vc\Parser\Constant(
            new \vc\Parser\Value( new \vc\Parser\Brackets )
        );

        $this->assertEquals(
            r8(new \vc\Data\Constant('NAME'))
                ->setValue(new \vc\Data\Value('NULL', 'null')),
            $parser->parseConstant( $access )
        );

        $this->assertHasToken( Token::T_CLASS, $access );
    }

    public function testParseConstant_Heredoc ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenAName('NAME')->thenSomeSpace()
                ->thenAnEquals()->thenSomeSpace()
                ->thenAHereDoc('contents')->thenASemicolon()
                ->thenAClass()
        );

        $parser = new \vc\Parser\Constant(
            new \vc\Parser\Value( new \vc\Parser\Brackets )
        );

        $this->assertEquals(
            r8(new \vc\Data\Constant('NAME'))
                ->setValue(new \vc\Data\Value('contents', 'string')),
            $parser->parseConstant( $access )
        );

        $this->assertHasToken( Token::T_CLASS, $access );
    }

}

?>