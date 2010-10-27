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
 * Finds a matching brace
 */
class Brackets
{

    /**
     * Using the given token types as open and close
     *
     * @param \vc\iface\Tokens\Reader $reader
     * @param Integer $open
     * @param Integer $close
     * @return String
     */
    private function parse (
        \vc\iface\Tokens\Reader $reader,
        array $open,
        $close
    ) {
        $depth = 0;
        $first = TRUE;
        $content = '';

        while ( $depth >= 0 && $reader->hasToken() ) {
            $token = $reader->popToken();
            $type = $token->getType();

            if ( $first )
            {
                $first = FALSE;
                if ( in_array($type, $open) )
                    continue;
            }

            if ( in_array($type, $open) )
                $depth++;
            else if ( $type == $close )
                $depth--;

            if ( $depth >= 0 )
                $content .= $token->getContent();
        }

        return $content;
    }

    /**
     * Parses a set of parenthesis
     *
     * @param \vc\iface\Tokens\Reader $reader
     * @return String
     */
    public function parseParens ( \vc\iface\Tokens\Reader $reader )
    {
        return $this->parse(
            $reader,
            array(Token::T_PARENS_OPEN),
            Token::T_PARENS_CLOSE
        );
    }

    /**
     * Parses a set of parenthesis
     *
     * @param \vc\iface\Tokens\Reader $reader
     * @return String
     */
    public function parseCurlies ( \vc\iface\Tokens\Reader $reader )
    {
        return $this->parse(
            $reader,
            array(Token::T_CURLY_OPEN, Token::T_DOLLAR_OPEN_CURLY_BRACES),
            Token::T_CURLY_CLOSE
        );
    }

}
