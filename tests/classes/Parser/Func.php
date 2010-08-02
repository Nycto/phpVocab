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
 * Unit tests
 */
class test_classes_Parser_Func extends \vc\Test\TestCase
{

    /**
     * Returns a function parser
     *
     * @return \vc\Parser\Func
     */
    public function getFuncParser ()
    {
        return new \vc\Parser\Func(
            new \vc\Parser\Routine(
                new \vc\Parser\Args(
                    new \vc\Parser\Path,
                    new \vc\Parser\Value(
                        new \vc\Parser\Brackets
                    )
                ),
                new \vc\Parser\Brackets
            )
        );
    }

    public function testParseFunc ()
    {
        $access = $this->getAccessParserWithComment(
            new \vc\Data\Comment('Data'),
            $this->oneTokenReader()->thenAFunction( 123 )
                ->thenSomeSpace()->thenAName('MyFunc')
                ->thenOpenParens()->thenCloseParens()
                ->thenAnOpenBlock()->thenACloseBlock()
        );

        $this->assertEquals(
            r8(new \vc\Data\Routine\Func(123, new \vc\Data\Comment('Data')))
                ->setName('MyFunc'),
            $this->getFuncParser()->parseFunc( $access )
        );

        $this->assertEndOfTokens( $access );
    }

}

?>