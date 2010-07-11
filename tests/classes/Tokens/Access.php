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
class test_classes_Tokens_Access extends PHPUnit_Framework_TestCase
{

    public function testFind_EmptyTokenSet ()
    {
        $reader = new \vc\Tokens\Access(
            new \vc\Tokens\Parser(
                new \r8\Stream\In\Void
            )
        );

        $this->assertNull(
            $reader->find(array(\vc\Tokens\Token::T_CLASS))
        );
    }

    public function testFind_TokenGetsFound ()
    {
        $reader = new \vc\Tokens\Access(
            new \vc\Tokens\Parser(
                new \r8\Stream\In\String("<?php echo 'content';?>")
            )
        );

        $this->assertEquals(
            new \vc\Tokens\Token(316, 'echo', 1),
            $reader->find(array(
                \vc\Tokens\Token::T_ECHO,
                \vc\Tokens\Token::T_SEMICOLON
            ))
        );

        $this->assertEquals(
            new \vc\Tokens\Token(-106, ';', 1),
            $reader->find(array(
                \vc\Tokens\Token::T_ECHO,
                \vc\Tokens\Token::T_SEMICOLON
            ))
        );

        $this->assertNull(
            $reader->find(array(
                \vc\Tokens\Token::T_ECHO,
                \vc\Tokens\Token::T_SEMICOLON
            ))
        );
    }

}

?>