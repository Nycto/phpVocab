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
class Access
{

    /**
     * The token being wrapped
     *
     * @var \vc\iface\Tokens\Reader
     */
    private $reader;

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
     * Returns the next token amongst the given type
     *
     * @param Array $types The list of types to search for
     * @return \vc\Tokens\Token Returns NULL if an appropriate token can not
     *      be found
     */
    public function find ( array $types )
    {
        while ( $this->reader->hasToken() ) {
            $token = $this->reader->popToken();
            if ( in_array($token->getType(), $types) )
                return $token;
        }
        return NULL;
    }

    /**
     * Returns the next token expcluding the given types
     *
     * @param Array $types The list of types to exclude
     * @return \vc\Tokens\Token Returns NULL if an appropriate token can not
     *      be found
     */
    public function findExcluding ( array $types )
    {
        while ( $this->reader->hasToken() ) {
            $token = $this->reader->popToken();
            if ( !in_array($token->getType(), $types) )
                return $token;
        }
        return NULL;
    }

    /**
     * Returns the next token amongst the given type, skipping any tokens
     * listed in the allowing list
     *
     * @throws \vc\Tokens\UnexpectedToken If a token does not exist in either
     *      input list, an exception will be thrown
     * @param Array $types The list of types to search for
     * @param Array $allowing The list of tokens to skip
     * @return \vc\Tokens\Token Returns NULL if an appropriate token can not
     *      be found
     */
    public function findAllowing ( array $types, array $allowing = array() )
    {
        while ( $this->reader->hasToken() ) {

            $token = $this->reader->peekAtToken();
            $type = $token->getType();

            if ( in_array($type, $types) )
                return $this->reader->popToken();

            else if ( !in_array($type, $allowing) )
                throw new \vc\Tokens\UnexpectedToken($token, $types, $allowing);

            $this->reader->popToken();
        }

        return NULL;
    }

}

?>