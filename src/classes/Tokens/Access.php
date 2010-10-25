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

        $search = new \vc\Tokens\Search( $comments );
        $search->setTokenMask(array(
            Token::T_WHITESPACE, Token::T_COMMENT,
            Token::T_DOC_COMMENT, Token::T_ML_COMMENT
        ));

        return new \vc\Tokens\Access( $comments, $search, $comments );
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
     * @see \vc\iface\Tokens\Search::copy
     */
    public function copy ( \vc\iface\Tokens\Reader $reader )
    {
        return new self(
            $reader,
            $this->search->copy( $reader ),
            $this->comments
        );
    }

    /**
     * @see \vc\iface\Tokens\Search::findRequired()
     */
    public function findRequired ( array $types, array $allowing = array() )
    {
        return $this->search->findRequired( $types, $allowing );
    }

    /**
     * @see \vc\iface\Tokens\Search::find()
     */
    public function find ( array $types, array $allowing = array() )
    {
        return $this->search->find( $types, $allowing );
    }

    /**
     * @see \vc\iface\Tokens\Search::peekToRequired()
     */
    public function peekToRequired ( array $types, array $allowing = array() )
    {
        return $this->search->peekToRequired( $types, $allowing );
    }

    /**
     * @see \vc\iface\Tokens\Search::peekTo
     */
    public function peekTo ( array $types, array $allowing = array() )
    {
        return $this->search->peekTo( $types, $allowing );
    }

    /**
     * @see \vc\iface\Tokens\Search::findSkipping
     */
    public function peekToSkipping ( array $types )
    {
        return $this->search->peekToSkipping($types);
    }

    /**
     * @see \vc\iface\Tokens\Comments::getComment
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
            $this->search->copy( $reader ),
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
            $this->search->copy( $reader ),
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
            $this->reader->popToken()->dump();
        }
    }

}

