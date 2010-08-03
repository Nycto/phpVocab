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
 * Unit test
 */
class test_classes_Data_Visibility extends \vc\Test\TestCase
{

    public function testFromToken ()
    {
        $this->assertEquals(
            \vc\Data\Visibility::vPublic(),
            \vc\Data\Visibility::fromToken(
                new \vc\Tokens\Token( Token::T_PUBLIC, 'public', 1 )
            )
        );

        $this->assertEquals(
            \vc\Data\Visibility::vProtected(),
            \vc\Data\Visibility::fromToken(
                new \vc\Tokens\Token( Token::T_PROTECTED, 'protected', 1 )
            )
        );

        $this->assertEquals(
            \vc\Data\Visibility::vPrivate(),
            \vc\Data\Visibility::fromToken(
                new \vc\Tokens\Token( Token::T_PRIVATE, 'private', 1 )
            )
        );

        try {
            \vc\Data\Visibility::fromToken(
                new \vc\Tokens\Token( Token::T_CLASS, 'class', 1 )
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {}
    }

}

?>