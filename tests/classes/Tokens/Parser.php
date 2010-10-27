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

use \vc\Tokens\Token;

require_once rtrim( __DIR__, "/" ) ."/../../setup.php";

/**
 * Unit test for running all the tests
 */
class test_classes_Tokens_Parser extends \vc\Test\TestCase
{

    public function testLookupToken ()
    {
        $this->assertEquals(
           Token::T_AMPERSAND,
           \vc\Tokens\Parser::lookupToken('&')
        );

        try {
            \vc\Tokens\Parser::lookupToken('blah');
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\Exception\UnrecognizedToken $err ) {}
    }

    public function test_EmptyInput ()
    {
        $parser = new \vc\Tokens\Parser( new \r8\Stream\In\Void );
        $this->assertEndOfTokens($parser);
        $this->assertEndOfTokens($parser);
        $this->assertEndOfTokens($parser);
    }

    public function test_TokenParsing ()
    {
        $parser = new \vc\Tokens\Parser(
            new \r8\Stream\In\String(
                "<?php echo 'content';?>"
            )
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new Token(Token::T_OPEN_TAG, '<?php ', 1),
            $parser->popToken()
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new Token(Token::T_ECHO, 'echo', 1),
            $parser->popToken()
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new Token(Token::T_WHITESPACE, ' ', 1),
            $parser->popToken()
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new Token(Token::T_CONSTANT_ENCAPSED_STRING, "'content'", 1),
            $parser->popToken()
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new Token(Token::T_SEMICOLON, ';', 1),
            $parser->popToken()
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new Token(Token::T_CLOSE_TAG, '?>', 1),
            $parser->popToken()
        );
    }

    public function testPopToken_CustomTokens ()
    {
        $parser = new \vc\Tokens\Parser(
            new \r8\Stream\In\String(
                '<?php if(!$$v[0]=1<5){'. "\n"
                .'~1%1*1+1-1/1&1^1|1>0?:`w`.@a("$a",1);'."\n"
                .'}?>'
            )
        );

        $result = array (
            array(Token::T_OPEN_TAG, '<?php ', 1), array(Token::T_IF, 'if', 1),
            array(Token::T_PARENS_OPEN, '(', 1), array(Token::T_LOGICAL_NOT, '!', 1),
            array(Token::T_VAR_VARIABLE, '$', 1), array(Token::T_VARIABLE, '$v', 1),
            array(Token::T_BRACKET_OPEN, '[', 1), array(Token::T_LNUMBER, '0', 1),
            array(Token::T_BRACKET_CLOSE, ']', 1), array(Token::T_EQUALS, '=', 1),
            array(Token::T_LNUMBER, '1', 1), array(Token::T_LESS_THAN, '<', 1),
            array(Token::T_LNUMBER, '5', 1), array(Token::T_PARENS_CLOSE, ')', 1),
            array(Token::T_CURLY_OPEN, '{', 1), array(Token::T_WHITESPACE, "\n", 1),
            array(Token::T_BIT_NOT, '~', 2), array(Token::T_LNUMBER, '1', 2),
            array(Token::T_MODULO, '%', 2), array(Token::T_LNUMBER, '1', 2),
            array(Token::T_MULTIPLY, '*', 2), array(Token::T_LNUMBER, '1', 2),
            array(Token::T_ADD, '+', 2), array(Token::T_LNUMBER, '1', 2),
            array(Token::T_MINUS, '-', 2), array(Token::T_LNUMBER, '1', 2),
            array(Token::T_DIVIDE, '/', 2), array(Token::T_LNUMBER, '1', 2),
            array(Token::T_AMPERSAND, '&', 2), array(Token::T_LNUMBER, '1', 2),
            array(Token::T_BIT_XOR, '^', 2), array(Token::T_LNUMBER, '1', 2),
            array(Token::T_BIT_OR, '|', 2), array(Token::T_LNUMBER, '1', 2),
            array(Token::T_GREATER_THAN, '>', 2), array(Token::T_LNUMBER, '0', 2),
            array(Token::T_TERNARY, '?', 2), array(Token::T_TERNARY_ELSE, ':', 2),
            array(Token::T_BACKTICK, '`', 2), array(Token::T_ENCAPSED_AND_WHITESPACE, 'w', 2),
            array(Token::T_BACKTICK, '`', 2), array(Token::T_CONCAT, '.', 2),
            array(Token::T_SUPPRESS, '@', 2), array(Token::T_STRING, 'a', 2),
            array(Token::T_PARENS_OPEN, '(', 2), array(Token::T_QUOTE, '"', 2),
            array(Token::T_VARIABLE, '$a', 2), array(Token::T_QUOTE, '"', 2),
            array(Token::T_COMMA, ',', 2), array(Token::T_LNUMBER, '1', 2),
            array(Token::T_PARENS_CLOSE, ')', 2), array(Token::T_SEMICOLON, ';', 2),
            array(Token::T_WHITESPACE, "\n", 2), array(Token::T_CURLY_CLOSE, '}', 3),
            array(Token::T_CLOSE_TAG, '?>', 3),
        );

        foreach( $result AS $offset => $token ) {
            $this->assertTrue(
                $parser->hasToken(),
                "Ran out of tokens at offset #$offset"
            );
            $this->assertEquals(
                Token::fromArray( $token ),
                $parser->popToken(),
                "Token mismatch at offset #$offset"
            );
        }

        $this->assertEndOfTokens($parser);
    }

    public function testCustomToken_WhiteSpace ()
    {
        $parser = new \vc\Tokens\Parser(
            new \r8\Stream\In\String("<?php \n  \r \n\n;")
        );

        $this->assertHasToken( Token::T_OPEN_TAG, $parser );
        $this->assertHasToken( Token::T_WHITESPACE, $parser );

        $semicolon = $parser->popToken();
        $this->assertIsTokenOf( Token::T_SEMICOLON, $semicolon );
        $this->assertEquals( 5, $semicolon->getLine() );
    }

    public function testPeekAtToken ()
    {
        $parser = new \vc\Tokens\Parser(
            new \r8\Stream\In\String(
                "<?php echo 'content';?>"
            )
        );

        $peek = $parser->peekAtToken();
        $this->assertIsTokenOf( Token::T_OPEN_TAG, $peek );
        $this->assertSame( $peek, $parser->peekAtToken() );
        $this->assertSame( $peek, $parser->popToken() );

        $peek2 = $parser->peekAtToken();
        $this->assertIsTokenOf( Token::T_ECHO, $peek2 );
        $this->assertSame( $peek2, $parser->peekAtToken() );
        $this->assertSame( $peek2, $parser->popToken() );
    }

}
