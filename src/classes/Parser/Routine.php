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
 * Parses the shared parts of a method or function
 */
class Routine
{

    /**
     * A parser for picking out the function arguments
     *
     * @var \vc\Parser\Args
     */
    private $args;

    /**
     * A parser for collecting the content of a function
     *
     * @var \vc\Parser\Brackets
     */
    private $brackets;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Args $args A parser for pulling out the function arguments
     * @param \vc\Parser\Brackets $brackets A parser for collecting the content
     *      of a function
     */
    public function __construct (
        \vc\Parser\Args $args,
        \vc\Parser\Brackets $brackets
    ) {
        $this->args = $args;
        $this->brackets = $brackets;
    }

    /**
     * Parse a function out of the given token reader
     *
     * @param \vc\Data\Routine $routine The object to fill with data
     * @param \vc\Tokens\Access $access The token access
     * @return \vc\Data\Routine\Func
     */
    public function parseRoutine (
        \vc\Data\Routine $routine,
        \vc\Tokens\Access $access
    ) {
        $access->findRequired(
            array(Token::T_FUNCTION), array(Token::T_WHITESPACE)
        );

        $token = $access->findRequired(
            array(Token::T_STRING, Token::T_AMPERSAND),
            array(Token::T_WHITESPACE)
        );

        // Handle routines that return a reference
        if ( $token->is(Token::T_AMPERSAND) ) {
            $routine->setReturnRef(TRUE);
            $token = $access->findRequired(
                array(Token::T_STRING), array(Token::T_WHITESPACE)
            );
        }

        $routine->setName( $token->getContent() );
        $routine->setArgs( $this->args->parseArgs($access) );

        $access->findRequired(
            array(Token::T_BLOCK_OPEN, Token::T_SEMICOLON),
            array(Token::T_WHITESPACE)
        );

        $this->brackets->parseCurlies( $access );
    }

}

?>