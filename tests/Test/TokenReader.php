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

namespace vc\Test;

/**
 * A test stub designed to return tokens in a specific order
 */
class TokenReader implements \vc\iface\Tokens\Reader
{

    /**
     * The tokens queued up in this stub
     *
     * @var Array
     */
    private $tokens = array();

    /**
     * The current token
     *
     * @var \vc\Tokens\Token
     */
    private $current;

    /**
     * Instantiates a new Token Stub
     *
     * @return \vc\Tokens\Stub
     */
    static public function one ()
    {
        return new self;
    }

    /**
     * Adds a token to this list
     *
     * @param Integer $type The type of token
     * @param String $value The value of this token
     * @param Integer $line The line number
     * @return \vc\Tokens\Stub Returns a self reference
     */
    public function then ( $type, $value, $line = 1 )
    {
        $this->tokens[] = new \vc\Tokens\Token( $type, $value, $line );
        return $this;
    }

    /**
     * Returns whether there are any more tokens to be read
     *
     * @return Boolean
     */
    public function hasToken ()
    {
        return !empty($this->tokens);
    }

    /**
     * Returns the next token
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function nextToken ()
    {
        $this->current = array_shift($this->tokens);
        return $this->current;
    }

    /**
     * Pushes the current token back onto the end of the reader so it will be
     * returned the next time someone calls nextToken
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function reinstateToken ()
    {
        array_unshift($this->tokens, $this->current);
        return $this;
    }

}

?>