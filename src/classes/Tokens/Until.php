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
 * Passes along tokens until a specific type is reached
 *
 * This will not include the interrupt token in the stream. It will stop the
 * token before
 */
class Until implements \vc\iface\Tokens\Reader
{

    /**
     * The list of Tokens that will halt this token reader
     *
     * @var Array
     */
    private $until;

    /**
     * The Token Reader being wrapped
     *
     * @var \vc\iface\Tokens\Reader
     */
    private $inner;

    /**
     * Whether this token reader has been halted because it reached one of its
     * interrupt tokens
     *
     * @var Boolean
     */
    private $active = TRUE;

    /**
     * Constructor...
     *
     * @param Array $until The list of tokens that will halt this token reader
     * @param \vc\Parser\Comment $parser The parser to use for building the
     *      Comment data objects
     */
    public function __construct ( array $until, \vc\iface\Tokens\Reader $inner )
    {
        $this->until = $until;
        $this->inner = $inner;
    }

    /**
     * Returns whether there are any more tokens to be read
     *
     * @return Boolean
     */
    public function hasToken ()
    {
        return $this->active && $this->inner->hasToken();
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

        // Peek at the next token to see if it is an interrupt
        $peek = $this->inner->peekAtToken();
        if ( !$peek || in_array($peek->getType(), $this->until) )
            $this->active = FALSE;

        return $next;
    }

    /**
     * Returns the next token in the stack without shifting it off the list
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function peekAtToken ()
    {
        if ( !$this->active )
            return NULL;

        return $this->inner->peekAtToken();
    }

}

