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
class Access implements \vc\iface\Tokens\Reader, \vc\iface\Tokens\Search
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
     * A helper function which builds an access object from a Token Reader
     *
     * @param \vc\iface\Tokens\Reader $reader
     * @return \vc\Tokens\Access
     */
    static public function buildAccess ( \vc\iface\Tokens\Reader $reader )
    {
        return new \vc\Tokens\Access(
            $reader,
            new \vc\Tokens\Search( $reader )
        );
    }

    /**
     * Constructor...
     *
     * @param \vc\iface\Tokens\Reader $reader The Token reader being wrapped
     * @param \vc\iface\Tokens\Search $search The search interface being wrapped
     */
    public function __construct (
        \vc\iface\Tokens\Reader $reader,
        \vc\iface\Tokens\Search $search
    ) {
        $this->reader = $reader;
        $this->search = $search;
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
     * @see \vc\iface\Tokens\Search::find()
     */
    public function find ( array $types )
    {
        return $this->search->find( $types );
    }

    /**
     * @see \vc\iface\Tokens\Search::findExcluding()
     */
    public function findExcluding ( array $types )
    {
        return $this->search->findExcluding( $types );
    }

    /**
     * @see \vc\iface\Tokens\Search::findAllowing()
     */
    public function findAllowing ( array $types, array $allowing = array() )
    {
        return $this->search->findAllowing( $types, $allowing );
    }

}

?>