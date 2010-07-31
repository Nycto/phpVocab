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
     * The parser used for extracting arrays
     *
     * @var \vc\Parser\Brackets
     */
    private $brackets;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Brackets $brackets
     */
    public function __construct ( \vc\Parser\Brackets $brackets )
    {
        $this->brackets = $brackets;
    }

    /**
     * Pareses a quoted string
     *
     * @param \vc\Tokens\Token $token
     * @return \vc\Data\Value
     */
    private function parseString ( \vc\Tokens\Token $token )
    {
        return new \vc\Data\Value(
            substr($token->getContent(), 1, -1),
            "string"
        );
    }

    /**
     * Parese a HereDoc declaration
     *
     * @param \vc\Tokens\Access $access
     * @return \vc\Data\Value
     */
    private function parseHereDoc ( \vc\Tokens\Access $access )
    {
        $token = $access->findRequired(
            array(Token::T_ENCAPSED_AND_WHITESPACE)
        );
        $access->findRequired(array(Token::T_END_HEREDOC));

        return new \vc\Data\Value( $token->getContent(), "string" );
    }

    /**
     * Parses a keyword
     *
     * @return \vc\Data\Value
     */
    private function parseKeyword ( \vc\Tokens\Token $token )
    {
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
    }

    /**
     * Parses an array
     *
     * @param \vc\Tokens\Access $access
     * @return \vc\Data\Value
     */
    private function parseArray ( \vc\Tokens\Access $access )
    {
        $token = $access->findRequired(
            array(Token::T_PARENS_OPEN),
            array(Token::T_WHITESPACE)
        );

        return new \vc\Data\Value(
            sprintf('array(%s)', $this->brackets->parseParens($access)),
            'array'
        );
    }

    /**
     * Parses a value from a token stream
     *
     * @param \vc\Tokens\Access $access
     * @return \vc\Data\Value
     */
    public function parseValue ( \vc\Tokens\Access $access )
    {
        $token = $access->findRequired(
            array(
                Token::T_LNUMBER, Token::T_DNUMBER, Token::T_STRING,
                Token::T_START_HEREDOC, Token::T_CONSTANT_ENCAPSED_STRING,
                Token::T_ARRAY
            ),
            array(Token::T_WHITESPACE, Token::T_EQUALS)
        );

        switch ( $token->getType() ) {

            // Strings
            case Token::T_CONSTANT_ENCAPSED_STRING:
                return $this->parseString( $token );

            // HereDocs
            case Token::T_START_HEREDOC:
                return $this->parseHereDoc( $access );

            // Integers
            case Token::T_LNUMBER:
                return new \vc\Data\Value( $token->getContent(), "int" );

            // Floats
            case Token::T_DNUMBER:
                return new \vc\Data\Value( $token->getContent(), "float" );

            // Keywords like true, false or null
            case Token::T_STRING:
                return $this->parseKeyword( $token );

            // Arrays
            case Token::T_ARRAY:
                return $this->parseArray( $access );

            default:
                throw new \RuntimeException("Unexpected Type");
        }
    }

}

?>