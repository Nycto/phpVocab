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
     * Returns the next token amongst the given type
     *
     * @param Array $types The list of types to search for
     * @return \vc\Tokens\Token Returns NULL if an appropriate token can not
     *      be found
     */
    public function find ( array $types );

    /**
     * Returns the next token expcluding the given types
     *
     * @param Array $types The list of types to exclude
     * @return \vc\Tokens\Token Returns NULL if an appropriate token can not
     *      be found
     */
    public function findExcluding ( array $types );

    /**
     * Returns the next token amongst the given type, skipping any tokens
     * listed in the allowing list
     *
     * @throws \vc\Tokens\UnexpectedToken If a token does not exist in either
     *      input list, an exception will be thrown when fatal is TRUE
     * @param Array $types The list of types to search for
     * @param Array $allowing The list of tokens to skip
     * @param Boolean $fatal Whether to throw an exception or simply return
     *      NULL when an unexpected token is encountered
     * @return \vc\Tokens\Token|NULL
     */
    public function findAllowing (
        array $types,
        array $allowing = array(),
        $fatal = TRUE
    );

}

?>