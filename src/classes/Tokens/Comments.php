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
 * Tracks the block comments as they flow through
 */
class Comments implements \vc\iface\Tokens\Reader, \vc\iface\Tokens\Comments
{

    /**
     * The Token Reader being wrapped
     *
     * @var \vc\iface\Tokens\Reader
     */
    private $inner;

    /**
     * The comment associated with the current context
     *
     * @var String
     */
    private $comment;

    /**
     * Constructor...
     *
     * @param \vc\iface\Tokens\Reader $inner The token reader being wrapped
     */
    public function __construct ( \vc\iface\Tokens\Reader $inner )
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
        return $this->inner->hasToken();
    }

    /**
     * Returns the next token
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function nextToken ()
    {
        $inner = $this->inner->nextToken();

        $type = $inner->getType();

        if ( $type == \vc\Tokens\Token::T_DOC_COMMENT )
            $this->comment = $inner->getContent();

        return $inner;
    }

    /**
     * Returns the block comment associated with the current context
     *
     * @return String
     */
    public function getComment ()
    {
        return $this->comment;
    }

    /**
     * Consumes the current comment so it can't be used again
     *
     * @return \vc\iface\Tokens\Comments
     */
    public function consumeComment ()
    {
        $this->comment = NULL;
        return $this;
    }

}

?>