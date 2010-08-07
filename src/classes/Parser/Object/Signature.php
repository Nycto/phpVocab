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
 * Parses the common keywords for methods and properties of a class
 */
class Signature
{

    /**
     * The parser for reading member properties
     *
     * @var \vc\Parser\Object\Property
     */
    private $properties;

    /**
     * The parser for reading methods
     *
     * @var \vc\Parser\Routine\Method
     */
    private $methods;

    /**
     * Constructor...
     */
    public function __construct (
        \vc\Parser\Object\Property $properties,
        \vc\Parser\Routine\Method $methods
    ) {
        $this->properties = $properties;
        $this->methods = $methods;
    }

    /**
     * Parses the given token reader
     *
     * @param \vc\Data\Object\Cls $class The class to fill with data
     * @param \vc\Tokens\Access $access The token access
     * @return NULL
     */
    public function parseSignature (
        \vc\Data\Type\Cls $class,
        \vc\Tokens\Access $access
    ) {
        $sig = new \vc\Data\Signature(
            $access->peekAtToken()->getLine(),
            $access->getComment()
        );

        // Keep collecting tokens until we find one that differentiates this
        // signature between a method and a property. This isn't as strict
        // as it could be, but it's already complex
        while (TRUE) {

            $token = $access->peekToRequired(
                array(
                    Token::T_STATIC,
                    Token::T_PUBLIC, Token::T_PROTECTED, Token::T_PRIVATE,
                    Token::T_ABSTRACT, Token::T_FINAL, Token::T_FUNCTION,
                    Token::T_VAR, Token::T_VARIABLE
                )
            );

            // These tokens denote a definite method
            if ($token->is(
                array(Token::T_ABSTRACT, Token::T_FINAL, Token::T_FUNCTION)
            )) {
                $class->addMethod(
                    $this->methods->parseMethod( $sig, $access )
                );
                return;
            }

            // These tokens denote a property
            else if ( $token->is(array(Token::T_VAR, Token::T_VARIABLE)) ) {
                $class->addProperty(
                    $this->properties->parseProperty( $sig, $access )
                );
                return;
            }

            // Methods and properties can both have visibility flags
            else if ( $token->is(
                array(Token::T_PUBLIC, Token::T_PROTECTED, Token::T_PRIVATE)
            )) {
                $sig->setVisibility(
                    \vc\Data\Visibility::fromToken( $token )
                );
            }

            // Methods and properties can both be static
            else if ( $token->is(Token::T_STATIC) ) {
                $sig->setStatic( TRUE );
            }

            // By this point, we have already determined that this token isn't
            // needed downstream, so we can pop it safely
            $access->popToken();
        }
    }

}

?>
