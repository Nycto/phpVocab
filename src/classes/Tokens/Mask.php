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

namespace vc\Tokens;

use vc\Tokens\Token as Token;

/**
 * Filters out a set of tokens from a wrapped stream
 */
class Mask implements \vc\iface\Tokens\Reader
{

    /**
     * The Token Reader being wrapped
     *
     * @var \vc\iface\Tokens\Reader
     */
    private $inner;

    /**
     * The list of tokens that will be masked
     *
     * @var Array
     */
    private $mask;

    /**
     * Creates a mask that will filter out comments
     *
     * @param \vc\iface\Tokens\Reader $inner The token reader being wrapped
     * @return \vc\Tokens\Mask
     */
    static public function comments ( \vc\iface\Tokens\Reader $inner )
    {
        return new self($inner, array(
            Token::T_COMMENT, Token::T_ML_COMMENT
        ));
    }

    /**
     * Constructor...
     *
     * @param \vc\iface\Tokens\Reader $inner The token reader being wrapped
     * @param Array $mask The list of tokens to mask
     */
    public function __construct ( \vc\iface\Tokens\Reader $inner, array $mask )
    {
        $this->inner = $inner;
        $this->mask = $mask;
    }

    /**
     * Returns the next token in the stack without shifting it off the list
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function peekAtToken ()
    {
        while ( $this->inner->hasToken() ) {

            $peek = $this->inner->peekAtToken();

            if ( !$peek->is($this->mask) )
                return $peek;

            $this->inner->popToken();
        }

        return NULL;
    }

    /**
     * Returns whether there are any more tokens to be read
     *
     * @return Boolean
     */
    public function hasToken ()
    {
        return $this->peekAtToken() !== NULL;
    }

    /**
     * Returns the next token
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function popToken ()
    {
        $this->peekAtToken();
        return $this->inner->popToken();
    }

}

