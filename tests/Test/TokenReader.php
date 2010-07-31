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

namespace vc\Test;

use vc\Tokens\Token as Token;

/**
 * A test stub designed to return tokens in a specific order
 */
class TokenReader implements \vc\iface\Tokens\Reader
{

    /**
     * The tokens queued up in this stub
     *
     * @var Array
     */
    private $tokens = array();

    /**
     * Instantiates a new Token Stub
     *
     * @return \vc\Tokens\Stub
     */
    static public function one ()
    {
        return new self;
    }

    /**
     * Adds a token to this list
     *
     * @param Integer $type The type of token
     * @param String $value The value of this token
     * @param Integer $line The line number
     * @return \vc\Tokens\Stub Returns a self reference
     */
    public function then ( $type, $value, $line = 1 )
    {
        $this->tokens[] = new Token( $type, $value, $line );
        return $this;
    }

    /**
     * Returns whether there are any more tokens to be read
     *
     * @return Boolean
     */
    public function hasToken ()
    {
        return !empty($this->tokens);
    }

    /**
     * Returns the next token
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function popToken ()
    {
        return array_shift($this->tokens) ?: NULL;
    }

    /**
     * Returns the next token in the stack without shifting it off the list
     *
     * @return \vc\Tokens\Token|NULL Returns NULL if no tokens are left
     */
    public function peekAtToken ()
    {
        return reset($this->tokens) ?: NULL;

    }

    /**
     * Adds an open block token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAnOpenBlock ( $line = 1 )
    {
        return $this->then( Token::T_BLOCK_OPEN, '{', $line );
    }

    /**
     * Adds a close block token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenACloseBlock ( $line = 1 )
    {
        return $this->then( Token::T_BLOCK_CLOSE, '}', $line );
    }

    /**
     * Adds a class token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAClass ( $line = 1 )
    {
        return $this->then( Token::T_CLASS, 'class', $line );
    }

    /**
     * Adds a function token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAFunction ( $line = 1 )
    {
        return $this->then( Token::T_FUNCTION, 'function', $line );
    }

    /**
     * Adds a call to echo
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAnEcho ( $line = 1 )
    {
        return $this->then( Token::T_ECHO, 'echo', $line );
    }

    /**
     * Adds an open tag
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAnOpenTag ( $line = 1 )
    {
        return $this->then( Token::T_OPEN_TAG, '<?php ', $line );
    }

    /**
     * Adds a close tag token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenACloseTag ( $line = 1 )
    {
        return $this->then( Token::T_CLOSE_TAG, '?>', $line );
    }

    /**
     * Adds a doc comment token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenADocComment ( $comment = '', $line = 1 )
    {
        return $this->then(
            Token::T_DOC_COMMENT,
            "/**\n * $comment\n */",
            $line
        );
    }

    /**
     * Adds a close tag token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenASemicolon ( $line = 1 )
    {
        return $this->then( Token::T_SEMICOLON, ';', $line );
    }

    /**
     * Adds a set of whitespace
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenSomeSpace ( $amount = 1, $line = 1 )
    {
        return $this->then(
            Token::T_WHITESPACE,
            str_repeat(' ', $amount),
            $line
        );
    }

    /**
     * Adds a Constant Encapsulated String
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAString ( $value, $quotedBy = "'", $line = 1 )
    {
        return $this->then(
            Token::T_CONSTANT_ENCAPSED_STRING,
            $quotedBy . $value . $quotedBy,
            $line
        );
    }

    /**
     * Adds the name of an class, function, etc
     *
     * Technically, this is a T_STRING token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAName ( $value, $line = 1 )
    {
        return $this->then( Token::T_STRING, $value, $line );
    }

    /**
     * Adds a namespace path
     *
     * @param String $namespace
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenANamespacePath ( $namespace, $line = 1 )
    {
        $namespace = explode('\\', $namespace);
        $this->then( Token::T_STRING, array_shift($namespace), $line );
        foreach ( $namespace AS $path ) {
            $this->then( Token::T_NS_SEPARATOR, '\\', $line )
                ->thenAName( $path );
        }
        return $this;
    }

    /**
     * Adds a namespace definition
     *
     * @param String $namespace
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenANamespace ( $namespace, $line = 1 )
    {
        return $this->then( Token::T_NAMESPACE, 'namespace', $line )
            ->thenSomeSpace()
            ->thenANamespacePath($namespace);
    }

    /**
     * Adds an equals token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAnEquals ( $line = 1 )
    {
        return $this->then( Token::T_EQUALS, '=', $line );
    }

    /**
     * Adds an integer token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAnInteger ( $int, $line = 1 )
    {
        return $this->then( Token::T_LNUMBER, (string) $int, $line );
    }

    /**
     * Adds a float token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAFloat ( $float, $line = 1 )
    {
        return $this->then( Token::T_DNUMBER, (string) $float, $line );
    }

    /**
     * Adds a Here doc token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAHereDoc ( $content, $line = 1 )
    {
        return $this->then( Token::T_START_HEREDOC, "<<<EOT\n", $line )
            ->then( Token::T_ENCAPSED_AND_WHITESPACE, $content, $line )
            ->then( Token::T_END_HEREDOC, 'EOT', $line );
    }

    /**
     * Adds an Open Parenthesis token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenOpenParens ( $line = 1 )
    {
        return $this->then( Token::T_PARENS_OPEN, "(", $line );
    }

    /**
     * Adds an Open Parenthesis token
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenCloseParens ( $line = 1 )
    {
        return $this->then( Token::T_PARENS_CLOSE, ")", $line );
    }

    /**
     * Adds an array definition to the token stream
     *
     * @return \vc\iface\Tokens\Reader Returns a self reference
     */
    public function thenAnArrayValue ( array $value )
    {
        $content = trim( '<?php '. var_export( $value, TRUE ) );
        $content = str_replace("\n", "", $content);
        $content = token_get_all($content);
        array_shift( $content );

        foreach ( $content AS $token )
        {
            if ( is_array($token) ) {
                $this->then(
                    $token[0],
                    $token[0] == Token::T_WHITESPACE ? ' ' : $token[1],
                    1
                );
            }
            else {
                $this->then(
                    \vc\Tokens\Parser::lookupToken($token),
                    $token,
                    1
                );
            }
        }

        return $this;
    }

}

?>