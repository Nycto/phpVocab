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
     * The list of tokens that will invalidate the current comment
     *
     * @var Array
     */
    static private $invalidate = array(
        \vc\Tokens\Token::T_SEMICOLON,
        \vc\Tokens\Token::T_BLOCK_OPEN,
        \vc\Tokens\Token::T_BLOCK_CLOSE,
        \vc\Tokens\Token::T_CLOSE_TAG,
    );

    /**
     * The parser to use for building the Comment Data objects
     *
     * @var \vc\Parser\Comment
     */
    private $parser;

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
     * @param \vc\Parser\Comment $parser The parser to use for building the
     *      Comment data objects
     * @param \vc\iface\Tokens\Reader $inner The token reader being wrapped
     */
    public function __construct (
        \vc\Parser\Comment $parser,
        \vc\iface\Tokens\Reader $inner
    ) {
        $this->parser = $parser;
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
    public function popToken ()
    {
        $next = $this->inner->popToken();

        $type = $next->getType();

        if ( $type == \vc\Tokens\Token::T_DOC_COMMENT )
            $this->comment = $next->getContent();

        else if ( in_array($type, self::$invalidate) )
            $this->comment = NULL;

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
        $this->inner->reinstateToken();
        return $this;
    }

    /**
     * Returns the block comment associated with the current context
     *
     * @return \vc\Data\Comment
     */
    public function getComment ()
    {
        $comment = $this->comment;
        $this->comment = NULL;

        if ( empty($comment) )
            return new \vc\Data\Comment;
        else
            return $this->parser->parse( $comment );
    }

}

?>