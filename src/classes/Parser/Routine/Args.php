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

namespace vc\Parser\Routine;

use \vc\Tokens\Token as Token;

/**
 * Parses a list of function arguments
 */
class Args
{

    /**
     * A parser for catching any argument type hinting
     *
     * @var \vc\Parser\Path
     */
    private $path;

    /**
     * A parser for reading default values
     *
     * @var \vc\Parser\Value
     */
    private $value;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Path $path Used to catch any argument type hinting
     * @param \vc\Parser\Value $value A parser for reading default values
     */
    public function __construct ( \vc\Parser\Path $path, \vc\Parser\Value $value )
    {
        $this->path = $path;
        $this->value = $value;
    }

    /**
     * Parses a single argument and returns the data that is created
     *
     * @param \vc\Tokens\Access $access
     * @return \vc\Data\Arg
     */
    private function parseArg ( \vc\Tokens\Access $access )
    {
        $arg = new \vc\Data\Arg;

        // We've already ensured this token is an appropriate type in the
        // calling method, so we don't need to do any extensive checking
        $token = $access->peekAtToken();

        // Parse out any type hinting
        if ( $token->is(array(
            Token::T_ARRAY, Token::T_STRING, Token::T_NS_SEPARATOR
        )) ) {
            $arg->setType(
                $token->is(Token::T_ARRAY)
                    ? 'array' : $this->path->parsePath($access)
            );
            $access->popToken();
            $token = $access->peekToRequired(
                array(Token::T_AMPERSAND, Token::T_VARIABLE)
            );
        }

        // Check to see if this argument is being passed by reference
        if ( $token->is(Token::T_AMPERSAND) ) {
            $arg->setReference(TRUE);
            $access->popToken();
            $token = $access->peekToRequired( array(Token::T_VARIABLE) );
        }

        // Grab the name of the argument. Up to this point, it has only been
        // peeked at, so we need to remove it from the list
        $arg->setVariable( $token->getContent() );
        $access->popToken();

        // Look for a default value for this argument
        $token = $access->find( array(Token::T_EQUALS) );
        if ( $token && $token->is(Token::T_EQUALS) )
            $arg->setDefault( $this->value->parseValue($access) );

        // Pop the comma off the list if one exists
        $access->find( array(Token::T_COMMA) );

        return $arg;
    }

    /**
     * Parses a list of method arguments
     *
     * @param \vc\Tokens\Access $access
     * @return Array
     */
    public function parseArgs ( \vc\Tokens\Access $access )
    {
        $access->findRequired( array(Token::T_PARENS_OPEN) );

        $args = array();

        // Continue parsing until we hit a close parenthesis
        while (TRUE) {

            $type = $access->peekToRequired(
                array(
                    Token::T_ARRAY, Token::T_STRING, Token::T_NS_SEPARATOR,
                    Token::T_AMPERSAND, Token::T_VARIABLE,
                    Token::T_PARENS_CLOSE
                )
            );

            if ( $type->is(Token::T_PARENS_CLOSE) )
                break;

            $args[] = $this->parseArg( $access );
        }

        $access->popToken();
        return $args;
    }

}


