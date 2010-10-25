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

/**
 * Unit test for running all the tests
 */
class test_classes_Tokens_Parser extends \vc\Test\TestCase
{

    public function testLookupToken ()
    {
        $this->assertEquals(
           \vc\Tokens\Token::T_AMPERSAND,
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
            new \vc\Tokens\Token(368, '<?php ', 1),
            $parser->popToken()
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new \vc\Tokens\Token(316, 'echo', 1),
            $parser->popToken()
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new \vc\Tokens\Token(371, ' ', 1),
            $parser->popToken()
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new \vc\Tokens\Token(315, "'content'", 1),
            $parser->popToken()
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new \vc\Tokens\Token(-106, ';', 1),
            $parser->popToken()
        );

        $this->assertTrue( $parser->hasToken() );
        $this->assertEquals(
            new \vc\Tokens\Token(370, '?>', 1),
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
            array(368, '<?php ', 1), array(301, 'if', 1), array(-113, '(', 1),
            array(-108, '!', 1), array(-124, '$', 1), array(309, '$v', 1),
            array(-115, '[', 1), array(305, '0', 1), array(-116, ']', 1),
            array(-101, '=', 1), array(305, '1', 1), array(-100, '<', 1),
            array(305, '5', 1), array(-114, ')', 1), array(-117, '{', 1),
            array(371, "\n", 1),
            array(-125, '~', 2), array(305, '1', 2), array(-122, '%', 2),
            array(305, '1', 2), array(-120, '*', 2), array(305, '1', 2),
            array(-123, '+', 2), array(305, '1', 2), array(-104, '-', 2),
            array(305, '1', 2), array(-110, '/', 2), array(305, '1', 2),
            array(-121, '&', 2), array(305, '1', 2), array(-126, '^', 2),
            array(305, '1', 2), array(-103, '|', 2), array(305, '1', 2),
            array(-102, '>', 2), array(305, '0', 2), array(-109, '?', 2),
            array(-107, ':', 2), array(-127, '`', 2), array(314, 'w', 2),
            array(-127, '`', 2), array(-111, '.', 2), array(-119, '@', 2),
            array(307, 'a', 2), array(-113, '(', 2), array(-112, '"', 2),
            array(309, '$a', 2), array(-112, '"', 2), array(-105, ',', 2),
            array(305, '1', 2), array(-114, ')', 2), array(-106, ';', 2),
            array(371, "\n", 2),
            array(-118, '}', 3), array(370, '?>', 3),
        );

        foreach( $result AS $offset => $token ) {
            $this->assertTrue(
                $parser->hasToken(),
                "Ran out of tokens at offset #$offset"
            );
            $this->assertEquals(
                \vc\Tokens\Token::fromArray( $token ),
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

        $this->assertHasToken( \vc\Tokens\Token::T_OPEN_TAG, $parser );
        $this->assertHasToken( \vc\Tokens\Token::T_WHITESPACE, $parser );

        $semicolon = $parser->popToken();
        $this->assertIsTokenOf( \vc\Tokens\Token::T_SEMICOLON, $semicolon );
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
        $this->assertIsTokenOf( T_OPEN_TAG, $peek );
        $this->assertSame( $peek, $parser->peekAtToken() );
        $this->assertSame( $peek, $parser->popToken() );

        $peek2 = $parser->peekAtToken();
        $this->assertIsTokenOf( T_ECHO, $peek2 );
        $this->assertSame( $peek2, $parser->peekAtToken() );
        $this->assertSame( $peek2, $parser->popToken() );
    }

}

