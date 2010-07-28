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

namespace vc\Data;

use \vc\Tokens\Token as Token;

/**
 * A value
 */
class Value
{

    /**
     * The actual value
     *
     * @var String
     */
    private $value;

    /**
     * The type of value
     *
     * @var String
     */
    private $type;

    /**
     * Builds a new instance using a token as input
     *
     * @param \vc\Tokens\Token $token
     * @return \vc\Data\Value
     */
    static public function buildFromToken ( \vc\Tokens\Token $token )
    {
        switch ( $token->getType() ) {

            case Token::T_CONSTANT_ENCAPSED_STRING:
                return new self(
                    substr($token->getContent(), 1, -1),
                    "string"
                );

            case Token::T_ENCAPSED_AND_WHITESPACE:
                return new self( $token->getContent(), "string" );

            case Token::T_LNUMBER:
                return new self( $token->getContent(), "int" );

            case Token::T_DNUMBER:
                return new self( $token->getContent(), "float" );

            case Token::T_STRING:
                $type = strtolower( $token->getContent() );

                if ( $type == "true" ||  $type == "false" )
                    $type = "bool";
                else if ( $type != "null" )
                    throw new \r8\Exception\Data("Unrecognized value type: ". $type);

                return new self( $token->getContent(), $type );

            default:
                throw new \vc\Tokens\UnexpectedToken(
                    $token,
                    array(
                        Token::T_LNUMBER, Token::T_DNUMBER, Token::T_STRING,
                        Token::T_CONSTANT_ENCAPSED_STRING,
                        Token::T_ENCAPSED_AND_WHITESPACE
                    )
                );
        }
    }

    /**
     * Constructor...
     *
     * @param String $value
     * @param String $type
     */
    public function __construct ( $value, $type )
    {
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * Returns the raw value
     *
     * @return String
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Returns the type of this value
     *
     * @return String
     */
    public function getType ()
    {
        return $this->type;
    }

}

?>