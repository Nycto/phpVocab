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
 * Tokenizes an input file according to PHP's token_get_all method
 */
class Parser implements \vc\iface\Tokens\Reader
{

    /**
     * The map of custom tokens
     *
     * @var Array
     */
    static private $tokenMap = array(
        '<' => \vc\Tokens\Token::T_LESS_THAN,
        '=' => \vc\Tokens\Token::T_EQUALS,
        '>' => \vc\Tokens\Token::T_GREATER_THAN,
        '|' => \vc\Tokens\Token::T_BIT_OR,
        '-' => \vc\Tokens\Token::T_MINUS,
        ',' => \vc\Tokens\Token::T_COMMA,
        ';' => \vc\Tokens\Token::T_SEMICOLON,
        ':' => \vc\Tokens\Token::T_TERNARY_ELSE,
        '!' => \vc\Tokens\Token::T_LOGICAL_NOT,
        '?' => \vc\Tokens\Token::T_TERNARY,
        '/' => \vc\Tokens\Token::T_DIVIDE,
        '.' => \vc\Tokens\Token::T_CONCAT,
        '"' => \vc\Tokens\Token::T_QUOTE,
        '(' => \vc\Tokens\Token::T_PARENS_OPEN,
        ')' => \vc\Tokens\Token::T_PARENS_CLOSE,
        '[' => \vc\Tokens\Token::T_BRACKET_OPEN,
        ']' => \vc\Tokens\Token::T_BRACKET_CLOSE,
        '{' => \vc\Tokens\Token::T_BLOCK_OPEN,
        '}' => \vc\Tokens\Token::T_BLOCK_CLOSE,
        '@' => \vc\Tokens\Token::T_SUPPRESS,
        '*' => \vc\Tokens\Token::T_MULTIPLY,
        '&' => \vc\Tokens\Token::T_BIT_AND,
        '%' => \vc\Tokens\Token::T_MODULO,
        '+' => \vc\Tokens\Token::T_ADD,
        '$' => \vc\Tokens\Token::T_VAR_VARIABLE,
        '~' => \vc\Tokens\Token::T_BIT_NOT,
        '^' => \vc\Tokens\Token::T_BIT_XOR,
        '`' => \vc\Tokens\Token::T_BACKTICK
    );

    /**
     * The list of tokens
     *
     * @var Array
     */
    private $tokens;

    /**
     * The current token value
     *
     * @var Array
     */
    private $current;

    /**
     * Constructor...
     *
     * @param \r8\iface\Stream\In $input The input stream
     */
    public function __construct ( \r8\iface\Stream\In $input )
    {
        $this->tokens = \token_get_all( $input->readAll() );
    }

    /**
     * Returns whether there are any more tokens to be read
     *
     * @return Boolean
     */
    public function hasToken ()
    {
        return !empty( $this->tokens );
    }

    /**
     * Returns the next token
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function nextToken ()
    {
        if ( empty($this->tokens) )
            return NULL;

        $new = array_shift( $this->tokens );

        if ( !is_array($new) )
        {
            if ( !isset(self::$tokenMap[$new]) )
                throw new \r8\Exception\Data($new, "Token", "Unrecognized Token");

            // For custom tokens, we need to derive the line number based on the
            // previous token. If the previous token contains a carriage return,
            // then we need to manually bump the value up
            $line = $this->current->getLine();
            if (
                $this->current->getType() == T_WHITESPACE
                && stripos( $this->current->getContent(), "\n" ) !== FALSE
            ) {
                $line++;
            }

            $new = array(self::$tokenMap[$new], $new, $line );
        }

        $this->current = \vc\Tokens\Token::fromArray($new);

        return $this->current;
    }

}

?>