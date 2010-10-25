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
class test_classes_Parser_Object_Signature extends \vc\Test\TestCase
{

    /**
     * Returns a signature parser that will apply the given callback when
     * a method is trying to be parsed
     *
     * @return \vc\Parser\Signature
     */
    public function getMethodSignatureParser ( $callback )
    {
        $property = $this->getStub('\vc\Parser\Object\Property');
        $property->expects( $this->never() )->method( "parserProperty" );

        $method = $this->getStub('\vc\Parser\Routine\Method');
        $method->expects( $this->once() )->method( "parseMethod" )
            ->with(
                $this->isInstanceOf('\vc\Data\Signature' ),
                $this->isInstanceOf('\vc\Tokens\Access' )
            )
            ->will( $this->returnCallback($callback) );

        return new \vc\Parser\Object\Signature( $property, $method );
    }

    /**
     * Returns a signature parser that will apply the given callback when
     * a property is trying to be parsed
     *
     * @return \vc\Parser\Signature
     */
    public function getPropertySignatureParser ( $callback )
    {
        $property = $this->getStub('\vc\Parser\Object\Property');
        $property->expects( $this->once() )->method( "parseProperty" )
            ->with(
                $this->isInstanceOf('\vc\Data\Signature' ),
                $this->isInstanceOf('\vc\Tokens\Access' )
            )
            ->will( $this->returnCallback($callback) );

        $method = $this->getStub('\vc\Parser\Routine\Method');
        $method->expects( $this->never() )->method( "parseMethod" );

        return new \vc\Parser\Object\Signature( $property, $method );
    }

    /**
     * Asserts that a token stream creates a signature matching the given value
     *
     * @return NULL
     */
    public function assertTokenStreamCreatesMethod (
        \vc\Data\Comment $comment,
        \vc\iface\Tokens\Reader $reader,
        \vc\Data\Signature $vs
    ) {
        // First, load up an access object to return the given comment
        $access = $this->getAccessParserWithComment( $comment, $reader );

        // This is a stub method. We will always be returned by the message
        // parser stub
        $method = new \vc\Data\Routine\Method(1);

        $assert = $this;
        $parser = $this->getMethodSignatureParser(
            function ($sig, $access) use ( $method, $assert, $vs ) {
                $access->popToken();
                $assert->assertEquals( $vs, $sig );
                return $method;
            }
        );

        // The method will be injected into this object
        $class = new \vc\Data\Type\Cls(1);

        $parser->parseSignature( $class, $access );

        // Assert that the new method was properly inserted
        $this->assertSame(array($method), $class->getMethods());

        $this->assertEndOfTokens( $reader );
    }

    /**
     * Asserts that a token stream creates a signature matching the given value
     *
     * @return NULL
     */
    public function assertTokenStreamCreatesProperty (
        \vc\Data\Comment $comment,
        \vc\iface\Tokens\Reader $reader,
        \vc\Data\Signature $vs
    ) {
        // First, load up an access object to return the given comment
        $access = $this->getAccessParserWithComment( $comment, $reader );

        // This method will always be used... it is returned by the parsing stub
        $prop = new \vc\Data\Property(1);

        $assert = $this;
        $parser = $this->getPropertySignatureParser(
            function ($sig, $access) use ( $prop, $assert, $vs ) {
                $access->popToken();
                $assert->assertEquals( $vs, $sig );
                return $prop;
            }
        );

        // The method will be injected into this object
        $class = new \vc\Data\Type\Cls(1);

        $parser->parseSignature( $class, $access );

        // Assert that the new property was properly inserted
        $this->assertSame(array($prop), $class->getProperties());

        $this->assertEndOfTokens( $reader );
    }

    public function testParseSignature_FunctionToken ()
    {
        $this->assertTokenStreamCreatesMethod(
            new \vc\Data\Comment('Note'),
            $this->oneTokenReader()->thenAFunction(50),
            r8( new \vc\Data\Signature(50, new \vc\Data\Comment('Note')) )
        );
    }

    public function testParseSignature_PublicMethod ()
    {
        $this->assertTokenStreamCreatesMethod(
            new \vc\Data\Comment('Note'),
            $this->oneTokenReader()->thenAPublic(50)->thenAFunction,
            r8( new \vc\Data\Signature(50, new \vc\Data\Comment('Note')) )
        );
    }

    public function testParseSignature_StaticPrivateMethod ()
    {
        $this->assertTokenStreamCreatesMethod(
            new \vc\Data\Comment('Note'),
            $this->oneTokenReader()->thenAStatic(50)->thenSomeSpace
                ->thenAPrivate->thenSomeSpace->thenAFunction,
            r8( new \vc\Data\Signature(50, new \vc\Data\Comment('Note')) )
                ->setStatic( TRUE )
                ->setVisibility( \vc\Data\Visibility::vPrivate() )
        );
    }

    public function testParseSignature_AbstractProtectedMethod ()
    {
        $this->assertTokenStreamCreatesMethod(
            new \vc\Data\Comment('Note'),
            $this->oneTokenReader()->thenAPrivate(50)
                ->thenSomeSpace->thenAnAbstract,
            r8( new \vc\Data\Signature(50, new \vc\Data\Comment('Note')) )
                ->setVisibility( \vc\Data\Visibility::vPrivate() )
        );
    }

    public function testParseSignature_FinalMethod ()
    {
        $this->assertTokenStreamCreatesMethod(
            new \vc\Data\Comment('Note'),
            $this->oneTokenReader()->thenAFinal(50),
            r8( new \vc\Data\Signature(50, new \vc\Data\Comment('Note')) )
        );
    }

    public function testParseSignature_PHP4StyleProperty ()
    {
        $this->assertTokenStreamCreatesProperty(
            new \vc\Data\Comment('Note'),
            $this->oneTokenReader()->then(Token::T_VAR, 'var', 50),
            r8( new \vc\Data\Signature(50, new \vc\Data\Comment('Note')) )
        );
    }

    public function testParseSignature_StaticPrivateProperty ()
    {
        $this->assertTokenStreamCreatesProperty(
            new \vc\Data\Comment('Note'),
            $this->oneTokenReader()->thenAStatic(50)
                ->thenSomeSpace->thenAPrivate
                ->thenSomeSpace->thenAVariable('$var'),
            r8( new \vc\Data\Signature(50, new \vc\Data\Comment('Note')) )
                ->setStatic(TRUE)
                ->setVisibility( \vc\Data\Visibility::vPrivate() )
        );
    }

    public function testParseSignature_PublicProperty ()
    {
        $this->assertTokenStreamCreatesProperty(
            new \vc\Data\Comment('Note'),
            $this->oneTokenReader()->thenAPublic(50)
                ->thenSomeSpace->thenAVariable('$var'),
            r8( new \vc\Data\Signature(50, new \vc\Data\Comment('Note')) )
        );
    }

    public function testParseSignature_ProtectedProperty ()
    {
        $this->assertTokenStreamCreatesProperty(
            new \vc\Data\Comment('Note'),
            $this->oneTokenReader()->thenAProtected(50)
                ->thenSomeSpace->thenAVariable('$var'),
            r8( new \vc\Data\Signature(50, new \vc\Data\Comment('Note')) )
                ->setVisibility( \vc\Data\Visibility::vProtected() )
        );
    }

}

