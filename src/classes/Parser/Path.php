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
 * Parses the path of a namespace
 */
class Path
{

    /**
     * Reads the path of a namespace
     *
     * @param \vc\iface\Tokens\Reader $reader The token source to pull from
     * @return NULL
     */
    public function parsePath ( \vc\iface\Tokens\Reader $reader )
    {
        // Skip past any white space
        while (
            $reader->hasToken()
            && $reader->peekAtToken()->is(Token::T_WHITESPACE)
        ) {
            $reader->popToken();
        }

        if ( !$reader->hasToken() )
            throw new \vc\Tokens\UnexpectedEnd;

        $reader->peekAtToken()->expect(array(
            Token::T_STRING, Token::T_NS_SEPARATOR
        ));

        $path = array();
        while ( $reader->hasToken() ) {
            $token = $reader->peekAtToken();

            if ( $token->is(array(Token::T_STRING, Token::T_NS_SEPARATOR)) )
                $path[] = $token->getContent();
            else
                break;

            $reader->popToken();
        }

        return implode('', $path);
    }

}

?>
