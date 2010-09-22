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
        '&' => \vc\Tokens\Token::T_AMPERSAND,
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
     * The current token
     *
     * We need to track this because not all tokens come with a line number.
     * In those cases, we use the previous token to determine what line we're
     * on.
     *
     * @var \vc\Tokens\Token
     */
    private $current;

    /**
     * Looks up a symbol token type from a string
     *
     * @param String $symbol
     * @return Integer
     */
    static public function lookupToken ( $symbol )
    {
        // For some simple tokens, token_get_all will just return a string of
        // the token. To normalize this, we have created new token values and
        // mapped them to the appropriate stirngs
        if ( !isset(self::$tokenMap[$symbol]) )
            throw new \vc\Tokens\Exception\UnrecognizedToken($symbol);

        return self::$tokenMap[$symbol];
    }

    /**
     * Constructor...
     *
     * @param \r8\iface\Stream\In $input The input stream
     */
    public function __construct ( \r8\iface\Stream\In $input )
    {
        $this->tokens = @\token_get_all( $input->readAll() );
    }

    /**
     * Builds a token from a mixed input
     *
     * @param Mixed $input
     * @return \vc\Tokens\Token
     */
    private function buildToken ( $input )
    {
        // In most cases, token_get_all represents a token as an array
        if ( is_array($input) )
            return \vc\Tokens\Token::fromArray($input);

        // When a token is reinstated, its object gets shifted onto this list
        if ( $input instanceof \vc\Tokens\Token )
            return $input;

        // For custom tokens, we need to derive the line number based on the
        // previous token. If the previous token contains a carriage return,
        // then we need to manually bump the value up
        $line = $this->current->getLine();
        if ( $this->current->is(\vc\Tokens\Token::T_WHITESPACE) ) {
            $content = $this->current->getContent();
            $line += substr_count($content, "\n") + substr_count($content, "\r");
        }

        return new \vc\Tokens\Token(
            self::lookupToken($input),
            $input,
            $line
        );
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
    public function popToken ()
    {
        if ( empty($this->tokens) )
            return NULL;

        $this->current = $this->buildToken( array_shift( $this->tokens ) );
        return $this->current;
    }

    /**
     * Returns the next token in the stack without shifting it off the list
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function peekAtToken ()
    {
        if ( empty($this->tokens) )
            return NULL;

        $this->tokens[0] = $this->buildToken( $this->tokens[0] );
        return $this->tokens[0];
    }

}

?>
