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

namespace vc\Parser\Object;

use \vc\Tokens\Token as Token;

/**
 * Parses the member properties, functions and constants from a class
 */
class Members
{

    /**
     * A parser for reading default values
     *
     * @var \vc\Parser\Constant
     */
    private $constant;

    /**
     * A parser for reading property and method signatures
     *
     * @var \vc\Parser\Object\Signature
     */
    private $signature;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Constant $value A parser for reading default values
     * @param \vc\Parser\Object\Signature $signature A parser for reading property
     *      and method signatures
     */
    public function __construct (
        \vc\Parser\Constant $constant,
        \vc\Parser\Object\Signature $signature
    ) {
        $this->constant = $constant;
        $this->signature = $signature;
    }

    /**
     * Parses the given token reader
     *
     * @param \vc\Data\Type\Cls $class The class to fill with data
     * @param \vc\Tokens\Access $access The token access
     * @return NULL
     */
    public function parseMembers (
        \vc\Data\Type\Cls $class,
        \vc\Tokens\Access $access
    ) {
        $last = NULL;

        // Limit the token stream to just the current block scope
        $access = $access->untilBlockEnds();

        // Keep looking until we have consumed all the members of this class
        while ( $access->hasToken() ) {

            $token = $access->peekToRequired(
                array(
                    Token::T_CONST,
                    Token::T_STATIC, Token::T_ABSTRACT, Token::T_FINAL,
                    Token::T_PUBLIC, Token::T_PROTECTED, Token::T_PRIVATE,
                    Token::T_VAR, Token::T_FUNCTION
                )
            );

            // This loop doesn't itself pop any tokens off, so this check just
            // ensures that the parsers below this one don't do anything
            // stupid.
            if ( $token === $last ) {
                throw new \RuntimeException(
                    'Possible Infinite Loop Detected. '
                    .'Current token has already been parsed'
                );
            }

            $last = $token;

            // Otherwise, we delegate to the appropraite parser
            if ( $token->is(Token::T_CONST) )
                $class->addConstant($this->constant->parseConstant($access));
            else
                $this->signature->parseSignature( $class, $access );
        }
    }

}

?>
