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

namespace vc\Parser\File;

use \vc\Tokens\Token as Token;

/**
 * Parses the namespaces out of a file
 */
class NSpaces
{

    /**
     * The parser for reading namespace paths
     *
     * @var \vc\Parser\Path
     */
    private $path;

    /**
     * The parser for a given namespace
     *
     * @var \vc\Parser\NSPace
     */
    private $nspace;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Path $path The parser for reading namespace paths
     * @param \vc\Parser\NSPace\Body $nspace The parser for a given namespace
     */
    public function __construct (
        \vc\Parser\Path $path,
        \vc\Parser\NSPace\Body $nspace
    ) {
        $this->path = $path;
        $this->nspace = $nspace;
    }

    /**
     * Finds the next namespace and returns whether one was found
     *
     * @param \vc\Tokens\Access $access The token access
     * @return Boolean
     */
    private function findNS ( \vc\Tokens\Access $access )
    {
        return NULL !== $access->find(
            array( Token::T_NAMESPACE ),
            array( Token::T_OPEN_TAG )
        );
    }

    /**
     * Put together the namespace path and get an access object for reading the
     * namespace
     *
     * Collect the path to this namespace until an ending token is found. This
     * ending token denotes whether this namespace is wrapped in curlies or not.
     *
     * @param \vc\Data\NSpace $nspace The namespace to append to
     * @param \vc\Tokens\Access $access The token access
     * @return \vc\Tokens\Access The access object for reading the namespace
     */
    private function buildNS ( \vc\Data\NSpace $nspace, \vc\Tokens\Access $access )
    {
        $nspace->setNamespace( $this->path->parsePath( $access ) );

        $token = $access->findRequired(
            array(Token::T_SEMICOLON, Token::T_BLOCK_OPEN)
        );

        if ( $token->is(Token::T_BLOCK_OPEN) )
            return $access->untilBlockEnds();
        else
            return $access->untilTokens(array(Token::T_NAMESPACE));
    }

    /**
     * Parses the given token reader
     *
     * @param \vc\Data\File $file The file to parse data into
     * @param \vc\Tokens\Access $access The token access
     * @return NULL
     */
    public function parse ( \vc\Data\File $file, \vc\Tokens\Access $access )
    {
        while ( $access->hasToken() ) {
            $nspace = new \vc\Data\NSpace;

            // If we can't find a namespace, then we are in the global scope
            if ( $this->findNS($access) )
                $inner = $this->buildNS( $nspace, $access );
            else
                $inner = $access->untilTokens(array(Token::T_NAMESPACE));

            $file->addNamespace( $nspace );
            $this->nspace->parseNSpace( $nspace, $inner );
        }
    }

}


