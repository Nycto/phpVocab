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

namespace vc\Parser\IFace;

use \vc\Tokens\Token as Token;

/**
 * Parses an interface declaration
 */
class Header
{

    /**
     * A parser that is used for pulling out the list of interface extensions
     *
     * @var \vc\Parser\PathList
     */
    private $pathList;

    /**
     * A parser for adding methods and constants to an interface
     *
     * @var \vc\Parser\IFace\Members
     */
    private $members;

    /**
     * Constructor...
     *
     * @param \vc\Parser\PathList $pathList A parser that is used for pulling
     *      out the list of interface extensions
     * @param \vc\Parser\IFace\Members $members A parser for adding methods
     *      and constants to an interface
     */
    public function __construct (
        \vc\Parser\PathList $pathList,
        \vc\Parser\IFace\Members $members
    ) {
        $this->pathList = $pathList;
        $this->members = $members;
    }

    /**
     * Parse an interface from token reader
     *
     * @param \vc\Tokens\Access $access The token access
     * @return \vc\Data\Routine\Func
     */
    public function parseIFace ( \vc\Tokens\Access $access )
    {
        $token = $access->findRequired( array(Token::T_INTERFACE) );

        $iface = new \vc\Data\Type\IFace(
            $token->getLine(), $access->getComment()
        );

        // Searches for the name of the interface
        $token = $access->findRequired( array(Token::T_STRING) );
        $iface->setName( $token->getContent() );


        // Look for any interfaces that this one extends
        $token = $access->findRequired(
            array(Token::T_EXTENDS, Token::T_CURLY_OPEN)
        );

        if ( $token->is(Token::T_EXTENDS) ) {
            $iface->setExtends( $this->pathList->parsePathList($access) );

            $access->findRequired( array(Token::T_CURLY_OPEN) );
        }

        // Finally, parse out the content of the class
        $this->members->parseMembers( $iface, $access );

        return $iface;
    }

}


