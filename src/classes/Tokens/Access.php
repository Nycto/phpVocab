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
 * Bundles multiple token access implementations into a single interface
 */
class Access implements \vc\iface\Tokens\Reader, \vc\iface\Tokens\Search, \vc\iface\Tokens\Comments
{

    /**
     * The Token reader being wrapped
     *
     * @var \vc\iface\Tokens\Reader
     */
    private $reader;

    /**
     * The search interface being wrapped
     *
     * @var \vc\iface\Tokens\Search
     */
    private $search;

    /**
     * Access for reading tokens
     *
     * @var \vc\iface\Tokens\Comments
     */
    private $comments;

    /**
     * A helper function which builds an access object from a Token Reader
     *
     * @param \vc\iface\Tokens\Reader $reader
     * @return \vc\Tokens\Access
     */
    static public function buildAccess ( \vc\iface\Tokens\Reader $reader )
    {
        $comments = new \vc\Tokens\Comments(
            new \vc\Parser\Comment,
            $reader
        );
        return new \vc\Tokens\Access(
            $comments,
            new \vc\Tokens\Search( $comments ),
            $comments
        );
    }

    /**
     * Constructor...
     *
     * @param \vc\iface\Tokens\Reader $reader The Token reader being wrapped
     * @param \vc\iface\Tokens\Search $search The search interface being wrapped
     * @param \vc\iface\Tokens\Comments $comments Access for reading tokens
     */
    public function __construct (
        \vc\iface\Tokens\Reader $reader,
        \vc\iface\Tokens\Search $search,
        \vc\iface\Tokens\Comments $comments
    ) {
        $this->reader = $reader;
        $this->search = $search;
        $this->comments = $comments;
    }

    /**
     * @see \vc\iface\Tokens\Reader::hasToken()
     */
    public function hasToken ()
    {
        return $this->reader->hasToken();
    }

    /**
     * @see \vc\iface\Tokens\Reader::popToken()
     */
    public function popToken ()
    {
        return $this->reader->popToken();
    }

    /**
     * @see \vc\iface\Tokens\Reader::peekAtToken()
     */
    public function peekAtToken ()
    {
        return $this->reader->peekAtToken();
    }

    /**
     * @see \vc\iface\Tokens\Search::findAllowing()
     */
    public function findAllowing (
        array $types,
        array $allowing = array(),
        $fatal = TRUE
    ) {
        return $this->search->findAllowing( $types, $allowing, $fatal );
    }

    /**
     * Returns the block comment associated with the current context
     *
     * @return \vc\Data\Comment
     */
    public function getComment ()
    {
        return $this->comments->getComment();
    }

    /**
     * Copies this access object, but wraps it so it will only read until the
     * given tokens are encountered
     *
     * @param Array $until The list of tokens that will halt this token reader
     * @return \vc\Tokens\Access
     */
    public function untilTokens ( array $until )
    {
        $reader = new \vc\Tokens\Until( $until, $this->reader );
        return new self(
            $reader,
            new \vc\Tokens\Search( $reader ),
            $this->comments
        );
    }

    /**
     * Copies this access object, but wraps it so it will only read until the
     * current block scope ends
     *
     * @return \vc\Tokens\Access
     */
    public function untilBlockEnds ()
    {
        $reader = new \vc\Tokens\BlockTrack( $this->reader );
        return new self(
            $reader,
            new \vc\Tokens\Search( $reader ),
            $this->comments
        );
    }

    /**
     * Dumps the remaining contents of this token reader to the output
     *
     * @return NULL
     */
    public function dump ()
    {
        while ( $this->reader->hasToken() ) {
            $token = $this->reader->popToken();
            printf(
                "Line %4d: %-26s (%4d): '%s'\n",
                $token->getLine(),
                $token->getName(),
                $token->getType(),
                str_replace(
                    array("\n", "\r"),
                    array('\n', '\r'),
                    $token->getContent()
                )
            );
        }
    }

}

?>