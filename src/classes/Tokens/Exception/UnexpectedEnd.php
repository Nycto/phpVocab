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

namespace vc\Tokens\Exception;

/**
 * Thrown when a reader needs more tokens but none are available
 */
class UnexpectedEnd extends \vc\Tokens\Exception
{

    /**
     * The name of the exception
     */
    const TITLE = "Unexpected End of Tokens Exception";

    /**
     * A description of this Exception
     */
    const DESCRIPTION = "A token reader expects more tokens but none are available";

    /**
     * Constructor...
     *
     * @param Array $search The list of tokens being searched for
     * @param Array $allowed The list of allowed tokens
     */
    public function __construct ( array $search, array $allowed = array() )
    {
        parent::__construct("Unexpected End of Tokens");

        $this->addData(
            "Searching for Tokens",
            implode(", ", array_map(
                array('\vc\Tokens\Token', 'getTokenName'),
                $search
            ))
        );
        $this->addData(
            "Allowed Tokens",
            implode(", ", array_map(
                array('\vc\Tokens\Token', 'getTokenName'),
                $allowed
            ))
        );
    }

}

