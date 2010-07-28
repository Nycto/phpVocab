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
 * Parses a value
 */
class Value
{

    /**
     * Parses a value from a token stream
     *
     * @param  \vc\iface\Tokens\Search $search
     * @return \vc\Data\Value
     */
    public function parseValue ( \vc\iface\Tokens\Search $search )
    {
        $token = $search->findAllowing(
            array(
                Token::T_LNUMBER, Token::T_DNUMBER, Token::T_STRING,
                Token::T_START_HEREDOC, Token::T_CONSTANT_ENCAPSED_STRING
            ),
            array(Token::T_WHITESPACE, Token::T_EQUALS)
        );

        switch ( $token->getType() ) {

            // Strings
            case Token::T_CONSTANT_ENCAPSED_STRING:
                return new \vc\Data\Value(
                    substr($token->getContent(), 1, -1),
                    "string"
                );

            // HereDocs
            case Token::T_START_HEREDOC:
                $token = $search->findAllowing(
                    array(Token::T_ENCAPSED_AND_WHITESPACE)
                );
                $search->findAllowing(array(Token::T_END_HEREDOC));

                return new \vc\Data\Value( $token->getContent(), "string" );

            // Integers
            case Token::T_LNUMBER:
                return new \vc\Data\Value( $token->getContent(), "int" );

            // Floats
            case Token::T_DNUMBER:
                return new \vc\Data\Value( $token->getContent(), "float" );

            // Keywords like true, false or null
            case Token::T_STRING:
                $type = strtolower( $token->getContent() );

                if ( $type == "true" ||  $type == "false" ) {
                    $type = "bool";
                }
                else if ( $type != "null" ) {
                    throw r8(new \r8\Exception\Data("Invalid String token value"))
                        ->addData("Type", $type)
                        ->addData("Token", $token);
                }

                return new \vc\Data\Value( $token->getContent(), $type );

            default:
                throw new \RuntimeException("Unexpected Type");
        }
    }

}

?>