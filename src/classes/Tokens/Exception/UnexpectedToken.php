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
 * Thrown when a parser encounters an unexpected token
 */
class UnexpectedToken extends \vc\Tokens\Exception
{

    /**
     * The name of the exception
     */
    const TITLE = "Unexpected Token Exception";

    /**
     * A description of this Exception
     */
    const DESCRIPTION = "A parser encountered an unexpected token";

    /**
     * Constructor...
     *
     * @param \vc\Tokens\Token $token The token that was encountered
     * @param Array $search The list of tokens being searched for
     * @param Array $allowed The list of allowed tokens
     */
    public function __construct (
        \vc\Tokens\Token $token,
        array $search,
        array $allowed = array()
    ) {
        parent::__construct(
            sprintf(
                'Unexpected Token (%s) on line %d',
                $token->getName(),
                $token->getLine()
            ),
            $search,
            $allowed
        );
        $this->addData( "Encountered Token", $token->getName() );
        $this->addData( "Token Line", $token->getLine() );
        $this->addData( "Token Content", $token->getContent() );
    }

}

?>