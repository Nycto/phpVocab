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

namespace vc\Parser\NSpace;

use \vc\Tokens\Token as Token;

/**
 * Parses the body of a namespace
 */
class Body
{

    /**
     * A parser for picking up aliases
     *
     * @var \vc\Parser\NSpace\Alias
     */
    private $alias;

    /**
     * The parser for picking up namespace constants
     *
     * @var \vc\Parser\Constant
     */
    private $constant;

    /**
     * A parser for picking up function declarations
     *
     * @var \vc\Parser\Routine\Func
     */
    private $func;

    /**
     * A parser for picking up class declarations
     *
     * @var \vc\Parser\Object\Header
     */
    private $object;

    /**
     * A parser for picking up interfaces
     *
     * @var \vc\Parser\IFace\Header
     */
    private $iface;

    /**
     * Constructor...
     *
     * @param \vc\Parser\NSpace\Alias $alias
     * @param \vc\Parser\Constant $constant
     * @param \vc\Parser\Routine\Func $func
     * @param \vc\Parser\Object\Header $object
     * @param \vc\Parser\IFace\Header $iface
     */
    public function __construct (
        \vc\Parser\NSpace\Alias $alias,
        \vc\Parser\Constant $constant,
        \vc\Parser\Routine\Func $func,
        \vc\Parser\Object\Header $object,
        \vc\Parser\IFace\Header $iface
    ) {
        $this->alias = $alias;
        $this->constant = $constant;
        $this->func = $func;
        $this->object = $object;
        $this->iface = $iface;
    }

    /**
     * Parses the given token reader
     *
     * @param \vc\Data\NSpace $nspace The namespace to parse data into
     * @param \vc\Tokens\Search $access The token access
     * @return NULL
     */
    public function parseNSpace (
        \vc\Data\NSpace $nspace,
        \vc\Tokens\Access $access
    ) {
        $last = NULL;

        // Keep looking until we have consumed all the tokens in this namespace
        while ( TRUE ) {

            $token = $access->peekToSkipping(array(
                Token::T_CLASS, Token::T_ABSTRACT,
                Token::T_CONST,
                Token::T_INTERFACE,
                Token::T_FUNCTION,
                Token::T_USE,
            ));

            if ( !$token )
                break;

            if ( $token === $last ) {
                throw new \RuntimeException(
                    'Possible Infinite Loop Detected. '
                    .'Current token has already been parsed'
                );
            }

            $last = $token;

            if ( $token->is(array(Token::T_CLASS, Token::T_ABSTRACT)) )
                $nspace->addType( $this->object->parseClass($access) );

            else if ( $token->is(Token::T_FUNCTION) )
                $nspace->addFunction( $this->func->parseFunc($access) );

            else if ( $token->is(Token::T_INTERFACE) )
                $nspace->addType( $this->iface->parseIFace($access) );

            else if ( $token->is(Token::T_CONST) )
                $nspace->addConstant( $this->constant->parseConstant($access) );

            else if ( $token->is(Token::T_USE) )
                $nspace->addAlias( $this->alias->parseAlias($access) );
        }

    }

}

?>