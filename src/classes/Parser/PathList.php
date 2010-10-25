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

namespace vc\Parser;

use \vc\Tokens\Token as Token;

/**
 * Parses a comma separated listed of namespace paths
 */
class PathList
{

    /**
     * Parser for extracting namespace paths for extends and implements clauses
     *
     * @var \vc\Parser\Path
     */
    private $path;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Path $path Parser for extracting namespace paths for
     *      extends and implements clauses
     */
    public function __construct ( \vc\Parser\Path $path )
    {
        $this->path = $path;
    }

    /**
     * Parses a list of namespace paths
     *
     * @param \vc\Tokens\Access $access The token access
     * @return Array Returns an array of Namespace Path Strings
     */
    public function parsePathList ( \vc\Tokens\Access $access )
    {
        $paths = array();

        // Keep looping until we encounter a curlie brace
        while (TRUE) {

            $paths[] = $this->path->parsePath($access);

            $token = $access->peekToRequired(
                array(Token::T_COMMA, Token::T_BLOCK_OPEN)
            );

            if ( $token->is(Token::T_COMMA) )
                $access->popToken();
            else
                break;
        }

        return $paths;
    }

}


