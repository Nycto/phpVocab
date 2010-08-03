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
class test_classes_Parser_Object_Property extends \vc\Test\TestCase
{

    /**
     * Returns a property parser
     *
     * @return \vc\Parser\Object\Property
     */
    public function getPropertyParser ()
    {
        return new \vc\Parser\Object\Property(
            new \vc\Parser\Value(
                new \vc\Parser\Brackets
            )
        );
    }

    public function testParseProperty_php4style ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->then(Token::T_VAR, 'var')
                ->thenSomeSpace()->thenAVariable('$var')
                ->thenASemicolon()
        );

        $sig = new \vc\Data\Signature(120, new \vc\Data\Comment('Note'));

        $this->assertEquals(
            r8(new \vc\Data\Property(120, new \vc\Data\Comment('Note')))
                ->setName('$var'),
            $this->getPropertyParser()->parseProperty($sig, $access)
        );

        $this->assertEndOfTokens( $access );
    }

    public function testParseProperty_public ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAPublic()
                ->thenSomeSpace()->thenAVariable('$var')
                ->thenASemicolon()
        );

        $sig = new \vc\Data\Signature(120, new \vc\Data\Comment('Note'));

        $this->assertEquals(
            r8(new \vc\Data\Property(120, new \vc\Data\Comment('Note')))
                ->setName('$var')
                ->setVisibility( \vc\Data\Visibility::vPublic() ),
            $this->getPropertyParser()->parseProperty($sig, $access)
        );

        $this->assertEndOfTokens( $access );
    }

    public function testParseProperty_private ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAPrivate()
                ->thenSomeSpace()->thenAVariable('$var')
                ->thenASemicolon()
        );

        $sig = new \vc\Data\Signature(120, new \vc\Data\Comment('Note'));

        $this->assertEquals(
            r8(new \vc\Data\Property(120, new \vc\Data\Comment('Note')))
                ->setName('$var')
                ->setVisibility( \vc\Data\Visibility::vPrivate() ),
            $this->getPropertyParser()->parseProperty($sig, $access)
        );

        $this->assertEndOfTokens( $access );
    }

    public function testParseProperty_protected ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAProtected()
                ->thenSomeSpace()->thenAVariable('$var')
                ->thenASemicolon()
        );

        $sig = new \vc\Data\Signature(120, new \vc\Data\Comment('Note'));

        $this->assertEquals(
            r8(new \vc\Data\Property(120, new \vc\Data\Comment('Note')))
                ->setName('$var')
                ->setVisibility( \vc\Data\Visibility::vProtected() ),
            $this->getPropertyParser()->parseProperty($sig, $access)
        );

        $this->assertEndOfTokens( $access );
    }

    public function testParseProperty_static ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAPublic()
                ->thenSomeSpace()->thenAStatic()
                ->thenSomeSpace()->thenAVariable('$var')
                ->thenASemicolon()
        );

        $sig = new \vc\Data\Signature(120, new \vc\Data\Comment('Note'));

        $this->assertEquals(
            r8(new \vc\Data\Property(120, new \vc\Data\Comment('Note')))
                ->setName('$var')->setStatic(TRUE),
            $this->getPropertyParser()->parseProperty($sig, $access)
        );

        $this->assertEndOfTokens( $access );
    }

    public function testParseProperty_defaultValue ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAPublic()
                ->thenSomeSpace()->thenAVariable('$var')
                ->thenSomeSpace()->thenAnEquals()->thenAnInteger(789)
                ->thenASemicolon()
        );

        $sig = new \vc\Data\Signature(120, new \vc\Data\Comment('Note'));

        $this->assertEquals(
            r8(new \vc\Data\Property(120, new \vc\Data\Comment('Note')))
                ->setName('$var')->setValue(new \vc\Data\Value(789, 'int')),
            $this->getPropertyParser()->parseProperty($sig, $access)
        );

        $this->assertEndOfTokens( $access );
    }

}

?>