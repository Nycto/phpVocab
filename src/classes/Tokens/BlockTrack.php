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

/**
 * Tracks the current curly block depth and artificially stops the token stream
 * when it has ended
 */
class BlockTrack implements \vc\iface\Tokens\Reader
{

    /**
     * The Token Reader being wrapped
     *
     * @var \vc\iface\Tokens\Reader
     */
    private $inner;

    /**
     * The current block depth
     *
     * When this reaches 0 we have exited the current scope
     *
     * @var Integer
     */
    private $depth = 1;

    /**
     * The number of tokens that have passed through this reader
     *
     * @var Integer
     */
    private $offset = 0;

    /**
     * The type of the current token
     *
     * @var Integer
     */
    private $type;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Comment $parser The parser to use for building the
     *      Comment data objects
     */
    public function __construct (\vc\iface\Tokens\Reader $inner )
    {
        $this->inner = $inner;
    }

    /**
     * Returns whether there are any more tokens to be read
     *
     * @return Boolean
     */
    public function hasToken ()
    {
        return $this->depth > 0 && $this->inner->hasToken();
    }

    /**
     * Returns the next token
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function popToken ()
    {
        if ( !$this->hasToken() )
            return NULL;

        $next = $this->inner->popToken();

        // Track the current type so we can adjust the depth if it is reinstanted
        $this->type = $next->getType();

        // If the first token we read is an open curly, it doesn't affect the depth
        if ( $this->offset > 0 )
        {
            if ( $this->type == \vc\Tokens\Token::T_BLOCK_OPEN )
                $this->depth++;

            else if ( $this->type == \vc\Tokens\Token::T_BLOCK_CLOSE )
                $this->depth--;
        }

        $this->offset++;

        return $next;
    }

    /**
     * Pushes the current token back onto the end of the reader so it will be
     * returned the next time someone calls popToken
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function reinstateToken ()
    {
        if ( $this->depth <= 0 )
            return $this;

        $this->offset--;

        // Only adjust the depth if this isn't the first token
        if ( $this->offset > 0 ) {

            if ( $this->type == \vc\Tokens\Token::T_BLOCK_OPEN )
                $this->depth--;

            else if ( $this->type == \vc\Tokens\Token::T_BLOCK_CLOSE )
                $this->depth++;
        }

        $this->inner->reinstateToken();

        return $this;
    }

}

?>