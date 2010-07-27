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
class test_classes_Parser_NSpace_Alias extends \vc\Test\TestCase
{

    public function testParseAlias_WithoutAs ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()
                ->thenANamespacePath('sub1\sub2')->thenASemicolon()
                ->thenAClass()
        );

        $parser = new \vc\Parser\NSpace\Alias( new \vc\Parser\Path );

        $this->assertEquals(
            new \vc\Data\Alias('sub1\sub2'),
            $parser->parseAlias( $access )
        );

        $this->assertHasToken( Token::T_CLASS, $access );
    }

    public function testParseAlias_WithAs ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenANamespacePath('sub1\sub2')
                ->thenSomeSpace()->then( Token::T_AS, 'as' )
                ->thenSomeSpace()->thenAName('renamed')
                ->thenASemicolon()
                ->thenAClass()
        );

        $parser = new \vc\Parser\NSpace\Alias( new \vc\Parser\Path );

        $this->assertEquals(
            r8(new \vc\Data\Alias('sub1\sub2'))->setAlias('renamed'),
            $parser->parseAlias( $access )
        );

        $this->assertHasToken( Token::T_CLASS, $access );
    }

    public function testParseAlias_MissingSemicolon ()
    {
        $access = \vc\Tokens\Access::buildAccess(
            $this->oneTokenReader()->thenANamespacePath('sub1\sub2')
                ->thenSomeSpace()->then( Token::T_AS, 'as' )
                ->thenSomeSpace()->thenAName('renamed')
        );

        $parser = new \vc\Parser\NSpace\Alias( new \vc\Parser\Path );

        try {
            $parser->parseAlias( $access );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \vc\Tokens\UnexpectedEnd $err ) {}
    }

}

?>