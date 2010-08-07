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

use \vc\Tokens\Token as Token;

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
     * Whether the first token has been popped or not
     *
     * @var Boolean
     */
    private $first = TRUE;

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
        return $this->peekAtToken() !== NULL;
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

        $type = $next->getType();

        // If the first token we read is an open curly, it doesn't affect the depth
        if ( $this->first )
            $this->first = FALSE;

        else if ( $type == Token::T_BLOCK_OPEN )
            $this->depth++;

        else if ( $type == Token::T_BLOCK_CLOSE )
            $this->depth--;

        return $next;
    }

    /**
     * Returns the next token in the stack without shifting it off the list
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function peekAtToken ()
    {
        if ( $this->depth <= 0 )
            return NULL;

        $token = $this->inner->peekAtToken();

        if ( $token->is(Token::T_BLOCK_CLOSE) && $this->depth == 1 ) {
            $this->depth--;
            return NULL;
        }
        else {
            return $token;
        }
    }

}

?>