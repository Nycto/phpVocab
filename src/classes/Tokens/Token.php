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
 * An individual token from a file
 */
class Token
{

    /**
     * Token constants
     *
     * We have redefined them because of inconsistencies in the token_get_all
     * method.
     */
    const T_LESS_THAN = -100;
    const T_EQUALS = -101;
    const T_GREATER_THAN = -102;
    const T_BIT_OR = -103;
    const T_MINUS = -104;
    const T_COMMA = -105;
    const T_SEMICOLON = -106;
    const T_TERNARY_ELSE = -107;
    const T_LOGICAL_NOT = -108;
    const T_TERNARY = -109;
    const T_DIVIDE = -110;
    const T_CONCAT = -111;
    const T_QUOTE = -112;
    const T_PARENS_OPEN = -113;
    const T_PARENS_CLOSE = -114;
    const T_BRACKET_OPEN = -115;
    const T_BRACKET_CLOSE = -116;
    const T_BLOCK_OPEN = -117;
    const T_BLOCK_CLOSE = -118;
    const T_SUPPRESS = -119;
    const T_MULTIPLY = -120;
    const T_AMPERSAND = -121;
    const T_MODULO = -122;
    const T_ADD = -123;
    const T_VAR_VARIABLE = -124;
    const T_BIT_NOT = -125;
    const T_BIT_XOR = -126;
    const T_BACKTICK = -127;
    const T_ABSTRACT = \T_ABSTRACT;
    const T_AND_EQUAL = \T_AND_EQUAL;
    const T_ARRAY = \T_ARRAY;
    const T_ARRAY_CAST = \T_ARRAY_CAST;
    const T_AS = \T_AS;
    const T_BAD_CHARACTER = \T_BAD_CHARACTER;
    const T_BOOLEAN_AND = \T_BOOLEAN_AND;
    const T_BOOLEAN_OR = \T_BOOLEAN_OR;
    const T_BOOL_CAST = \T_BOOL_CAST;
    const T_BREAK = \T_BREAK;
    const T_CASE = \T_CASE;
    const T_CATCH = \T_CATCH;
    const T_CHARACTER = \T_CHARACTER;
    const T_CLASS = \T_CLASS;
    const T_CLASS_C = \T_CLASS_C;
    const T_CLONE = \T_CLONE;
    const T_CLOSE_TAG = \T_CLOSE_TAG;
    const T_COMMENT = \T_COMMENT;
    const T_CONCAT_EQUAL = \T_CONCAT_EQUAL;
    const T_CONST = \T_CONST;
    const T_CONSTANT_ENCAPSED_STRING = \T_CONSTANT_ENCAPSED_STRING;
    const T_CONTINUE = \T_CONTINUE;
    const T_CURLY_OPEN = \T_CURLY_OPEN;
    const T_DEC = \T_DEC;
    const T_DECLARE = \T_DECLARE;
    const T_DEFAULT = \T_DEFAULT;
    const T_DIR = \T_DIR;
    const T_DIV_EQUAL = \T_DIV_EQUAL;
    const T_DNUMBER = \T_DNUMBER;
    const T_DOC_COMMENT = \T_DOC_COMMENT;
    const T_DO = \T_DO;
    const T_DOLLAR_OPEN_CURLY_BRACES = \T_DOLLAR_OPEN_CURLY_BRACES;
    const T_DOUBLE_ARROW = \T_DOUBLE_ARROW;
    const T_DOUBLE_CAST = \T_DOUBLE_CAST;
    const T_DOUBLE_COLON = \T_DOUBLE_COLON;
    const T_ECHO = \T_ECHO;
    const T_ELSE = \T_ELSE;
    const T_ELSEIF = \T_ELSEIF;
    const T_EMPTY = \T_EMPTY;
    const T_ENCAPSED_AND_WHITESPACE = \T_ENCAPSED_AND_WHITESPACE;
    const T_ENDDECLARE = \T_ENDDECLARE;
    const T_ENDFOR = \T_ENDFOR;
    const T_ENDFOREACH = \T_ENDFOREACH;
    const T_ENDIF = \T_ENDIF;
    const T_ENDSWITCH = \T_ENDSWITCH;
    const T_ENDWHILE = \T_ENDWHILE;
    const T_END_HEREDOC = \T_END_HEREDOC;
    const T_EVAL = \T_EVAL;
    const T_EXIT = \T_EXIT;
    const T_EXTENDS = \T_EXTENDS;
    const T_FILE = \T_FILE;
    const T_FINAL = \T_FINAL;
    const T_FOR = \T_FOR;
    const T_FOREACH = \T_FOREACH;
    const T_FUNCTION = \T_FUNCTION;
    const T_FUNC_C = \T_FUNC_C;
    const T_GLOBAL = \T_GLOBAL;
    const T_GOTO = \T_GOTO;
    const T_HALT_COMPILER = \T_HALT_COMPILER;
    const T_IF = \T_IF;
    const T_IMPLEMENTS = \T_IMPLEMENTS;
    const T_INC = \T_INC;
    const T_INCLUDE = \T_INCLUDE;
    const T_INCLUDE_ONCE = \T_INCLUDE_ONCE;
    const T_INLINE_HTML = \T_INLINE_HTML;
    const T_INSTANCEOF = \T_INSTANCEOF;
    const T_INT_CAST = \T_INT_CAST;
    const T_INTERFACE = \T_INTERFACE;
    const T_ISSET = \T_ISSET;
    const T_IS_EQUAL = \T_IS_EQUAL;
    const T_IS_GREATER_OR_EQUAL = \T_IS_GREATER_OR_EQUAL;
    const T_IS_IDENTICAL = \T_IS_IDENTICAL;
    const T_IS_NOT_EQUAL = \T_IS_NOT_EQUAL;
    const T_IS_NOT_IDENTICAL = \T_IS_NOT_IDENTICAL;
    const T_IS_SMALLER_OR_EQUAL = \T_IS_SMALLER_OR_EQUAL;
    const T_LINE = \T_LINE;
    const T_LIST = \T_LIST;
    const T_LNUMBER = \T_LNUMBER;
    const T_LOGICAL_AND = \T_LOGICAL_AND;
    const T_LOGICAL_OR = \T_LOGICAL_OR;
    const T_LOGICAL_XOR = \T_LOGICAL_XOR;
    const T_METHOD_C = \T_METHOD_C;
    const T_MINUS_EQUAL = \T_MINUS_EQUAL;
    const T_ML_COMMENT = \T_COMMENT;
    const T_MOD_EQUAL = \T_MOD_EQUAL;
    const T_MUL_EQUAL = \T_MUL_EQUAL;
    const T_NAMESPACE = \T_NAMESPACE;
    const T_NS_C = \T_NS_C;
    const T_NS_SEPARATOR = \T_NS_SEPARATOR;
    const T_NEW = \T_NEW;
    const T_NUM_STRING = \T_NUM_STRING;
    const T_OBJECT_CAST = \T_OBJECT_CAST;
    const T_OBJECT_OPERATOR = \T_OBJECT_OPERATOR;
    const T_OPEN_TAG = \T_OPEN_TAG;
    const T_OPEN_TAG_WITH_ECHO = \T_OPEN_TAG_WITH_ECHO;
    const T_OR_EQUAL = \T_OR_EQUAL;
    const T_PAAMAYIM_NEKUDOTAYIM = \T_PAAMAYIM_NEKUDOTAYIM;
    const T_PLUS_EQUAL = \T_PLUS_EQUAL;
    const T_PRINT = \T_PRINT;
    const T_PRIVATE = \T_PRIVATE;
    const T_PUBLIC = \T_PUBLIC;
    const T_PROTECTED = \T_PROTECTED;
    const T_REQUIRE = \T_REQUIRE;
    const T_REQUIRE_ONCE = \T_REQUIRE_ONCE;
    const T_RETURN = \T_RETURN;
    const T_SL = \T_SL;
    const T_SL_EQUAL = \T_SL_EQUAL;
    const T_SR = \T_SR;
    const T_SR_EQUAL = \T_SR_EQUAL;
    const T_START_HEREDOC = \T_START_HEREDOC;
    const T_STATIC = \T_STATIC;
    const T_STRING = \T_STRING;
    const T_STRING_CAST = \T_STRING_CAST;
    const T_STRING_VARNAME = \T_STRING_VARNAME;
    const T_SWITCH = \T_SWITCH;
    const T_THROW = \T_THROW;
    const T_TRY = \T_TRY;
    const T_UNSET = \T_UNSET;
    const T_UNSET_CAST = \T_UNSET_CAST;
    const T_USE = \T_USE;
    const T_VAR = \T_VAR;
    const T_VARIABLE = \T_VARIABLE;
    const T_WHILE = \T_WHILE;
    const T_WHITESPACE = \T_WHITESPACE;
    const T_XOR_EQUAL = \T_XOR_EQUAL;

    /**
     * A map of token constants to their human readable form
     *
     * @var Array
     */
    static private $readable;

    /**
     * The type of token this is
     *
     * @var Integer
     */
    private $type;

    /**
     * The content of this token
     *
     * @var String
     */
    private $content;

    /**
     * The line on which this token was found
     *
     * @var Integer
     */
    private $line;

    /**
     * Returns the name of a token given a Token ID
     *
     * @param Integer $tokenID
     * @return String|NULL Returns NULL if the token doesn't exist
     */
    static public function getTokenName ( $tokenID )
    {
        $tokenID = (int) $tokenID;

        // First check if this is a built in token
        $builtIn = token_name( $tokenID );
        if ( !empty($builtIn) && $builtIn !== "UNKNOWN" )
            return $builtIn;

        // Build a list of names to tokens for the custom tokens
        if ( empty(self::$readable) ) {
            $refl = new \ReflectionClass(__CLASS__);
            self::$readable = \array_flip(
                array_filter(
                    $refl->getConstants(),
                    '\r8\num\negative'
                )
            );
        }

        return isset(self::$readable[$tokenID])
            ? self::$readable[$tokenID] : NULL;
    }

    /**
     * Constructs a new instace of this object using an array as the source data
     *
     * @return \vc\Tokens\Token
     */
    static public function fromArray ( array $data )
    {
        return new self(
            isset($data[0]) ? $data[0] : 0,
            isset($data[1]) ? $data[1] : '',
            isset($data[2]) ? $data[2] : 0
        );
    }

    /**
     * Constructor...
     *
     * @param Integer $type The type of token
     * @param String $content The actual value of this token
     * @param Integer $line The line on which this token was found
     */
    public function __construct ( $type, $content, $line )
    {
        $this->type = (int) $type;
        $this->content = (string) $content;
        $this->line = (int) $line;
    }

    /**
     * Returns the type of this token
     *
     * @return Integer
     */
    public function getType ()
    {
        return $this->type;
    }

    /**
     * Returns the Content of this token
     *
     * @return String
     */
    public function getContent ()
    {
        return $this->content;
    }

    /**
     * Returns the Line on which this token appeared
     *
     * @return Integer
     */
    public function getLine ()
    {
        return $this->line;
    }

    /**
     * Returns the name of this token
     *
     * @return String|NULL Returns NULL if the token type is unknown
     */
    public function getName ()
    {
        return self::getTokenName( $this->type );
    }

    /**
     * Returns whether this token is of a given type
     *
     * @param Array|Integer $type The token types to check for
     * @return Boolean
     */
    public function is ( $type )
    {
        return in_array($this->type, (array) $type);
    }

    /**
     * A method that peeks at the next token and throws an exception if it isn't
     * one of the given values
     *
     * @param Array|Integer $type The token types to check for
     * @return \vc\Tokens\Token Returns a self reference
     */
    public function expect ( $type )
    {
        if ( !$this->is( $type ) )
            throw new \vc\Tokens\Exception\UnexpectedToken( $this, (array) $type );
        return $this;
    }

    /**
     * Converts this token back to an array, like the one returned by
     * token_get_all
     *
     * @return Array
     */
    public function toArray ()
    {
        return array( $this->type, $this->content, $this->line );
    }

    /**
     * Outputs a dump of this token to the client
     *
     * @return NULL
     */
    public function dump ()
    {
        printf(
            "Line %4d: %-26s (%4d): '%s'\n",
            $this->line,
            $this->getName(),
            $this->type,
            str_replace(
                array("\n", "\r"),
                array('\n', '\r'),
                $this->content
            )
        );
    }

}

