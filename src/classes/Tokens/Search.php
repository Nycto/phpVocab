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
 * Providers helper access functionality around a token parser
 */
class Search implements \vc\iface\Tokens\Search
{

    /**
     * The token being wrapped
     *
     * @var \vc\iface\Tokens\Reader
     */
    private $reader;

    /**
     * Any tokens that should be skipped by default
     *
     * @var array
     */
    private $mask = array();

    /**
     * Constructor...
     *
     * @param \vc\iface\Tokens\Reader $reader The token reader being wrapped
     */
    public function __construct ( \vc\iface\Tokens\Reader $reader )
    {
        $this->reader = $reader;
    }

    /**
     * Sets the list of general tokens to skip over
     *
     * @param Array $tokens The list of tokens to skip
     * @return \vc\Tokens\Search Returns a self reference
     */
    public function setTokenMask ( array $mask )
    {
        $this->mask = $mask;
        return $this;
    }

    /**
     * @see \vc\iface\Tokens\Search::copy
     */
    public function copy ( \vc\iface\Tokens\Reader $reader )
    {
        return r8(new self($reader))->setTokenMask( $this->mask );
    }

    /**
     * @see \vc\iface\Tokens\Search::peekToRequired
     */
    public function peekToRequired ( array $types, array $allowing = array() )
    {
        while ( $this->reader->hasToken() ) {

            $token = $this->reader->peekAtToken();
            $type = $token->getType();

            if ( in_array($type, $types) )
                return $token;

            else if (!in_array($type, $allowing) && !in_array($type, $this->mask))
                throw new \vc\Tokens\Exception\UnexpectedToken($token, $types, $allowing);

            $this->reader->popToken();
        }

        throw new \vc\Tokens\Exception\UnexpectedEnd($types, $allowing);
    }

    /**
     * @see \vc\iface\Tokens\Search::peekTo
     */
    public function peekTo ( array $types, array $allowing = array() )
    {
        try {
            return $this->peekToRequired( $types, $allowing );
        }
        catch ( \vc\Tokens\Exception\UnexpectedToken $err ) {
            return NULL;
        }
        catch ( \vc\Tokens\Exception\UnexpectedEnd $err ) {
            return NULL;
        }
    }

    /**
     * @see \vc\iface\Tokens\Search::findRequired
     */
    public function findRequired ( array $types, array $allowing = array() )
    {
        $found = $this->peekToRequired( $types, $allowing );
        $this->reader->popToken();
        return $found;
    }

    /**
     * @see \vc\iface\Tokens\Search::find
     */
    public function find ( array $types, array $allowing = array() )
    {
        try {
            return $this->findRequired( $types, $allowing );
        }
        catch ( \vc\Tokens\Exception\UnexpectedToken $err ) {
            return NULL;
        }
        catch ( \vc\Tokens\Exception\UnexpectedEnd $err ) {
            return NULL;
        }
    }

    /**
     * @see \vc\iface\Tokens\Search::findSkipping
     */
    public function peekToSkipping ( array $types )
    {
        while ( $this->reader->hasToken() ) {

            $token = $this->reader->peekAtToken();
            $type = $token->getType();

            if ( in_array($type, $types) )
                return $token;

            $this->reader->popToken();
        }
    }

}

