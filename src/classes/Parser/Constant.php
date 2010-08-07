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

namespace vc\Parser;

use \vc\Tokens\Token as Token;

/**
 * Parses an Alias declaration
 */
class Constant
{

    /**
     * The parser for constructing value objects
     *
     * @var \vc\Parser\Value
     */
    private $value;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Value $value The parser for constructing value objects
     */
    public function __construct ( \vc\Parser\Value $value )
    {
        $this->value = $value;
    }

    /**
     * Parses the given token reader
     *
     * @param \vc\Tokens\Access $access The token access
     * @return \vc\Data\Constant Returns the created constant
     */
    public function parseConstant ( \vc\Tokens\Access $access )
    {
        $access->findRequired( array(Token::T_CONST) );

        $name = $access->findRequired( array(Token::T_STRING) );

        $const = new \vc\Data\Constant( $name->getContent() );

        $access->findRequired( array(Token::T_EQUALS) );

        $const->setValue( $this->value->parseValue($access) );

        $access->findRequired( array(Token::T_SEMICOLON) );

        return $const;
    }

}

?>
