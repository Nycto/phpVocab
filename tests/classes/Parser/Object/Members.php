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
class test_classes_Parser_Object_Members extends \vc\Test\TestCase
{

    /**
     * Asserts that the given token reader will cause a signature to be parsed
     *
     * @return NULL
     */
    public function assertParsesToSignature ( \vc\iface\Tokens\Reader $reader )
    {
        $access = \vc\Tokens\Access::buildAccess( $reader );

        $constParser = $this->getStub('\vc\Parser\Constant');
        $constParser->expects( $this->never() )->method( "parseConstant" );

        $sigParser = $this->getStub('\vc\Parser\Object\Signature');
        $sigParser->expects( $this->once() )->method( "parseSignature" )
            ->with(
                $this->isInstanceOf('\vc\Data\Type\Cls'),
                $this->isInstanceOf('\vc\Tokens\Access')
            )
            ->will( $this->returnCallback(function ($cls, $access) {
                $access->popToken();
            } ) );

        $parser = new \vc\Parser\Object\Members( $constParser, $sigParser );

        $parser->parseMembers( new \vc\Data\Type\Cls(1), $access );

        $this->assertEndOfTokens( $access );

    }

    public function testParseConstant ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAConst()
        );

        $constant = new \vc\Data\Constant('CONST');

        $constParser = $this->getStub('\vc\Parser\Constant');
        $constParser->expects( $this->once() )->method( "parseConstant" )
            ->with( $this->isInstanceOf('\vc\Tokens\Access') )
            ->will( $this->returnCallback(function ($access) use ($constant) {
                $access->popToken();
                return $constant;
            } ) );

        $sigParser = $this->getStub('\vc\Parser\Object\Signature');
        $sigParser->expects( $this->never() )->method( "parseSignature" );

        $class = new \vc\Data\Type\Cls(1);

        $parser = new \vc\Parser\Object\Members( $constParser, $sigParser );

        $parser->parseMembers( $class, $access );

        $this->assertSame( array($constant), $class->getConstants() );
        $this->assertEndOfTokens( $access );
    }

    public function testParseSignature_Final ()
    {
        $this->assertParsesToSignature( $this->oneTokenReader()->thenAFinal() );
    }

    public function testParseSignature_Static ()
    {
        $this->assertParsesToSignature( $this->oneTokenReader()->thenAStatic() );
    }

    public function testParseSignature_Visibility ()
    {
        $this->assertParsesToSignature( $this->oneTokenReader()->thenAPublic() );
        $this->assertParsesToSignature( $this->oneTokenReader()->thenAPrivate() );
        $this->assertParsesToSignature( $this->oneTokenReader()->thenAProtected() );
    }

    public function testParseSignature_Var ()
    {
        $this->assertParsesToSignature(
            $this->oneTokenReader()->then(Token::T_VAR, 'var')
        );
    }

    public function testParseSignature_Function ()
    {
        $this->assertParsesToSignature(
            $this->oneTokenReader()->thenSomeSpace()->thenAFunction()
        );
    }

}

?>