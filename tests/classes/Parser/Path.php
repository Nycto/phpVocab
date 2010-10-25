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
class test_classes_Parser_Path extends \vc\Test\TestCase
{

    public function testParsePath_UntilUnrecognizedToken ()
    {
        $reader = $this->oneTokenReader()
            ->thenSomeSpace->thenANamespacePath( 'sub1\sub2\sub3' )
            ->thenAClass;

        $parser = new \vc\Parser\Path;

        $this->assertSame( 'sub1\sub2\sub3', $parser->parsePath( $reader ) );
        $this->assertHasToken( Token::T_CLASS, $reader );
    }

    public function testParsePath_UntilEndOfTokens ()
    {
        $reader = $this->oneTokenReader()
            ->thenSomeSpace->thenANamespacePath( 'sub1\sub2\sub3' );

        $parser = new \vc\Parser\Path;

        $this->assertSame( 'sub1\sub2\sub3', $parser->parsePath( $reader ) );
        $this->assertEndOfTokens( $reader );
    }

    public function testParsePath_EndOfTokensWithoutReadingAnyPath ()
    {
        $reader = $this->oneTokenReader()->thenSomeSpace;

        $parser = new \vc\Parser\Path;

        try {
            $parser->parsePath( $reader );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\Exception\UnexpectedEnd $err ) {}
    }

    public function testParsePath_UnexpectedTokenWithoutANamespace ()
    {
        $reader = $this->oneTokenReader()->thenAClass;

        $parser = new \vc\Parser\Path;

        try {
            $parser->parsePath( $reader );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\Exception\UnexpectedToken $err ) {}
    }

}

