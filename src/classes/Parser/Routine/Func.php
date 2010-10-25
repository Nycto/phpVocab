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

namespace vc\Parser\Routine;

use \vc\Tokens\Token as Token;

/**
 * Parses a function declaration
 */
class Func
{

    /**
     * A parser for extracting the meat of a function
     *
     * @var \vc\Parser\Routine
     */
    private $routine;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Routine\Body $routine A parser for extracting the meat of
     *      a function
     */
    public function __construct ( \vc\Parser\Routine\Body $routine )
    {
        $this->routine = $routine;
    }

    /**
     * Parse a function out of the given token reader
     *
     * @param \vc\Tokens\Access $access The token access
     * @return \vc\Data\Routine\Func
     */
    public function parseFunc ( \vc\Tokens\Access $access )
    {
        $token = $access->peekToRequired( array(Token::T_FUNCTION) );

        $func = new \vc\Data\Routine\Func(
            $token->getLine(), $access->getComment()
        );

        $this->routine->parseRoutine( $func, $access );

        return $func;
    }

}


