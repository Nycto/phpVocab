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

namespace vc\iface\Tokens;

/**
 * Searches a token stream for specific tokens
 */
interface Search
{

    /**
     * Returns the next token amongst the given type, skipping any tokens
     * listed in the allowing list
     *
     * @throws \vc\Tokens\UnexpectedToken If a token does not exist in either
     *      input list an exception will be thrown
     * @param Array $types The list of types to search for
     * @param Array $allowing The list of tokens to skip
     * @return \vc\Tokens\Token
     */
    public function findRequired ( array $types, array $allowing = array() );

    /**
     * Returns the next token amongst the given type, skipping any tokens
     * listed in the allowing list
     *
     * @param Array $types The list of types to search for
     * @param Array $allowing The list of tokens to skip
     * @return \vc\Tokens\Token|NULL Returns NULL if the token can't be found
     */
    public function find ( array $types, array $allowing = array() );

    /**
     * Returns the next token amongst the given type, without consuming that
     * token. This will skip any tokens listed in the allowing list.
     *
     * @throws \vc\Tokens\UnexpectedToken If a token does not exist in either
     *      input list an exception will be thrown
     * @param Array $types The list of types to search for
     * @param Array $allowing The list of tokens to skip
     * @return \vc\Tokens\Token
     */
    public function peekToRequired ( array $types, array $allowing = array() );

    /**
     * Searches for a token in the given list, passing by all other tokens
     *
     * This will not consume the token, but queue it up the be popped off next
     *
     * @param Array $types The list of types to search for
     * @return \vc\Tokens\Token|NULL Returns NULL if a token can't be found
     */
    public function peekToSkipping ( array $types );

}

?>