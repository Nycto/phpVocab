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
 * Errors thrown while traversing a token stream
 */
abstract class Exception extends \r8\Exception
{

    /**
     * Constructor...
     *
     * @param String $message The error message to pass on
     * @param Array $search The list of tokens being searched for
     * @param Array $allowed The list of allowed tokens
     */
    public function __construct ( $message, array $search, array $allowed = array() )
    {
        parent::__construct($message);

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

?>