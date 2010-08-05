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
class test_classes_Parser_Routine_Method extends \vc\Test\TestCase
{

    /**
     * Returns a Method Parser
     *
     * @return \vc\Parser\Routine\Method
     */
    public function getMethodParser ()
    {
        return new \vc\Parser\Routine\Method(
            new \vc\Parser\Routine\Body(
                new \vc\Parser\Routine\Args(
                    new \vc\Parser\Path,
                    new \vc\Parser\Value(
                        new \vc\Parser\Brackets
                    )
                ),
                new \vc\Parser\Brackets
            )
        );
    }

    public function testParseMethod_Sparse ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAFunction()
                ->thenSomeSpace()->thenAName('MyFunc')
                ->thenOpenParens()->thenCloseParens()
                ->thenAnOpenBlock()->thenACloseBlock()
        );

        $sig = new \vc\Data\Signature(123, new \vc\Data\Comment('Note'));

        $this->assertEquals(
            r8( new \vc\Data\Routine\Method(123, new \vc\Data\Comment('Note')) )
                ->setName('MyFunc')
                ->setVisibility( \vc\Data\Visibility::vPublic() ),
            $this->getMethodParser()->parseMethod($sig, $access)
        );
    }

    public function testParseMethod_Public ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAPublic()
                ->thenSomeSpace()->thenAFunction()
                ->thenSomeSpace()->thenAName('MyFunc')
                ->thenOpenParens()->thenCloseParens()
                ->thenAnOpenBlock()->thenACloseBlock()
        );

        $sig = new \vc\Data\Signature(123, new \vc\Data\Comment('Note'));

        $this->assertEquals(
            r8( new \vc\Data\Routine\Method(123, new \vc\Data\Comment('Note')) )
                ->setName('MyFunc')
                ->setVisibility( \vc\Data\Visibility::vPublic() ),
            $this->getMethodParser()->parseMethod($sig, $access)
        );
    }

    public function testParseMethod_StaticFinalProtectedMethod ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAStatic()->thenSomeSpace()
                ->thenAFinal()->thenSomeSpace()->thenAProtected()
                ->thenSomeSpace()->thenAFunction()
                ->thenSomeSpace()->thenAName('MyFunc')
                ->thenOpenParens()->thenCloseParens()
                ->thenAnOpenBlock()->thenACloseBlock()
        );

        $sig = new \vc\Data\Signature(123);

        $this->assertEquals(
            r8( new \vc\Data\Routine\Method(123) )
                ->setName('MyFunc')->setStatic(TRUE)->setFinal(TRUE)
                ->setVisibility( \vc\Data\Visibility::vProtected() ),
            $this->getMethodParser()->parseMethod($sig, $access)
        );
    }

    public function testParseMethod_AbstractPrivateMethod ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenAnAbstract()
                ->thenSomeSpace()->thenAPrivate()
                ->thenSomeSpace()->thenAFunction()
                ->thenSomeSpace()->thenAName('MyFunc')
                ->thenOpenParens()->thenCloseParens()
                ->thenAnOpenBlock()->thenACloseBlock()
        );

        $sig = new \vc\Data\Signature(123);

        $this->assertEquals(
            r8( new \vc\Data\Routine\Method(123) )
                ->setName('MyFunc')->setAbstract(TRUE)
                ->setVisibility( \vc\Data\Visibility::vPrivate() ),
            $this->getMethodParser()->parseMethod($sig, $access)
        );
    }

}

?>