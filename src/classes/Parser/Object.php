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
 * Parses a class declaration
 */
class Object
{

    /**
     * Parser for extracting namespace paths for extends and implements clauses
     *
     * @var \vc\Parser\Path
     */
    private $path;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Path $path Parser for extracting namespace paths for
     *      extends and implements clauses
     */
    public function __construct ( \vc\Parser\Path $path )
    {
        $this->path = $path;
    }

    /**
     * Parses an extends clause
     *
     * @param \vc\Data\Type\Cls $class The class to insert data into
     * @param \vc\Tokens\Access $access The token access
     * @return NULL
     */
    private function parseImplements (
        \vc\Data\Type\Cls $class,
        \vc\Tokens\Access $access
    ) {
        $class->addIFace( $this->path->parsePath($access) );

        $token = $access->findRequired(
            array(Token::T_COMMA, Token::T_BLOCK_OPEN),
            array(Token::T_WHITESPACE)
        );

        // Recursively collect the implemented paths
        if ( $token->is(Token::T_COMMA) )
            $this->parseImplements( $class, $access );
    }

    /**
     * Parses an extends clause
     *
     * @param \vc\Data\Type\Cls $class The class to insert data into
     * @param \vc\Tokens\Access $access The token access
     * @return NULL
     */
    private function parseExtends (
        \vc\Data\Type\Cls $class,
        \vc\Tokens\Access $access
    ) {
        $class->setExtends( $this->path->parsePath($access) );

        // Look for any interfaces
        $token = $access->findRequired(
            array(Token::T_IMPLEMENTS, Token::T_BLOCK_OPEN),
            array(Token::T_WHITESPACE)
        );

        if ( $token->is(Token::T_IMPLEMENTS) )
            $this->parseImplements( $class, $access );
    }

    /**
     * Parse a class from token reader
     *
     * @param \vc\Tokens\Access $access The token access
     * @return \vc\Data\Routine\Func
     */
    public function parseClass ( \vc\Tokens\Access $access )
    {
        $token = $access->findRequired(
            array(Token::T_CLASS, Token::T_ABSTRACT),
            array(Token::T_WHITESPACE)
        );

        $class = new \vc\Data\Type\Cls(
            $token->getLine(), $access->getComment()
        );

        // Extract the abstract flag from the class definition
        if ( $token->is(Token::T_ABSTRACT) ) {
            $class->setAbstract(TRUE);
            $access->findRequired(
                array(Token::T_CLASS), array(Token::T_WHITESPACE)
            );
        }

        $token = $access->findRequired(
            array(Token::T_STRING),array(Token::T_WHITESPACE)
        );

        // Pull the name of the class
        $class->setName( $token->getContent() );

        // Look for classes and interfaces
        $token = $access->findRequired(
            array(Token::T_EXTENDS, Token::T_IMPLEMENTS, Token::T_BLOCK_OPEN),
            array(Token::T_WHITESPACE)
        );

        if ( $token->is(Token::T_EXTENDS) )
            $this->parseExtends( $class, $access );
        else if ( $token->is(Token::T_IMPLEMENTS) )
            $this->parseImplements( $class, $access );

        return $class;
    }

}

?>