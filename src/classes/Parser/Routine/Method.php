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
 * Parses a class method
 */
class Method
{

    /**
     * A parser for extracting the meat of a function
     *
     * @var \vc\Parser\Routine
     */
    private $routine;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Routine\Body $routine A parser for extracting the meat of
     *      a function
     */
    public function __construct ( \vc\Parser\Routine\Body $routine )
    {
        $this->routine = $routine;
    }

    /**
     * Parses the given token reader
     *
     * @param \vc\Data\Signature $signature The base data from which to build
     *      the method
     * @param \vc\Tokens\Access $access The token stream
     * @return \vc\Data\Routine\Method
     */
    public function parseMethod (
        \vc\Data\Signature $signature,
        \vc\Tokens\Access $access
    ) {
        $method = $signature->buildMethod();

        // Continue iterating until we encounter a Function token
        while (TRUE) {

            $token = $access->peekToRequired(
                array(
                    Token::T_STATIC, Token::T_ABSTRACT, Token::T_FINAL,
                    Token::T_PUBLIC, Token::T_PROTECTED, Token::T_PRIVATE,
                    Token::T_FUNCTION
                )
            );

            // We don't want to consume the function token because the routine
            // parser looks for it. Break before we get a chance to pop it off
            // the stream
            if ( $token->is(Token::T_FUNCTION) )
                break;

            $access->popToken();

            if ($token->is(
                array(Token::T_PUBLIC, Token::T_PROTECTED, Token::T_PRIVATE)
            )) {
                $method->setVisibility(\vc\Data\Visibility::fromToken($token));
            }
            else if ( $token->is(Token::T_STATIC) ) {
                $method->setStatic(TRUE);
            }
            else if ( $token->is(Token::T_ABSTRACT) ) {
                $method->setAbstract(TRUE);
            }
            else if ( $token->is(Token::T_FINAL) ) {
                $method->setFinal(TRUE);
            }
        }

        $this->routine->parseRoutine( $method, $access );

        return $method;
    }

}


