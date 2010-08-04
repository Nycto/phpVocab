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

namespace vc\Parser\NSpace;

use \vc\Tokens\Token as Token;

/**
 * Parses an Alias declaration
 */
class Alias
{

    /**
     * The parser for reading namespace paths
     *
     * @var \vc\Parser\Path
     */
    private $path;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Path $path The parser for reading namespace paths
     * @param \vc\Parser\NSPace $nspace The parser for a given namespace
     */
    public function __construct ( \vc\Parser\Path $path )
    {
        $this->path = $path;
    }

    /**
     * Parses the given token reader
     *
     * @param \vc\Tokens\Access $access The token access
     * @return \vc\Data\Alias Returns the created alias
     */
    public function parseAlias ( \vc\Tokens\Access $access )
    {
        $access->findRequired(
            array(Token::T_USE), array(Token::T_WHITESPACE)
        );

        $alias = new \vc\Data\Alias( $this->path->parsePath($access) );

        $as = $access->find(
            array(Token::T_AS), array(Token::T_WHITESPACE)
        );

        if ( $as )
            $alias->setAlias( $this->path->parsePath($access) );

        $access->findRequired(
            array(Token::T_SEMICOLON), array(Token::T_WHITESPACE)
        );

        return $alias;
    }

}

?>