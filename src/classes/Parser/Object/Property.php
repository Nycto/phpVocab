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
 * Parses a class property
 */
class Property
{

    /**
     * A parser for reading default values
     *
     * @var \vc\Parser\Value
     */
    private $value;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Value $value A parser for reading default values
     */
    public function __construct ( \vc\Parser\Value $value )
    {
        $this->value = $value;
    }

    /**
     * Parses the given token reader
     *
     * @param \vc\Data\Signature $signature The signature to use as a source
     * @param \vc\Tokens\Access $access The token access
     * @return \vc\Data\Property
     */
    public function parseProperty (
        \vc\Data\Signature $signature,
        \vc\Tokens\Access $access
    ) {
        $prop = $signature->buildProperty();

        // Keep looking for modifier tokens until we reach the variable.
        // This isn't as strict as it could be about token order, but it greatly
        // simplifies the method to do it like this.
        do {
            $token = $access->findRequired(
                array(
                    Token::T_STATIC, Token::T_VAR, Token::T_VARIABLE,
                    Token::T_PUBLIC, Token::T_PROTECTED, Token::T_PRIVATE,
                )
            );

            if ( $token->is(
                array(Token::T_PUBLIC, Token::T_PROTECTED, Token::T_PRIVATE)
            ) ) {
                $prop->setVisibility( \vc\Data\Visibility::fromToken($token) );
            }
            else if ( $token->is(Token::T_STATIC) ) {
                $prop->setStatic( TRUE );
            }

        } while ( !$token->is(Token::T_VARIABLE) );

        $prop->setName( $token->getContent() );

        $token = $access->findRequired(
            array( Token::T_SEMICOLON, Token::T_EQUALS )
        );

        // Look for any default value
        if ( $token->is( Token::T_EQUALS ) ) {
            $prop->setValue( $this->value->parseValue($access) );
            $access->findRequired( array( Token::T_SEMICOLON ) );
        }

        return $prop;
    }

}


