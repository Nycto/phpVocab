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

namespace vc\Data;

use \vc\Tokens\Token as Token;

/**
 * The visibility of a method or property
 */
class Visibility extends \r8\Enum
{
    const vPUBLIC = 'public';
    const vPRIVATE = 'private';
    const vPROTECTED = 'protected';

    /**
     * Builds a new instance of this enum using a token to determine the value
     *
     * @param \vc\Tokens\Token $token
     * @return \vc\Data\Visibility
     */
    static public function fromToken ( \vc\Tokens\Token $token )
    {
        if ( $token->is(Token::T_PUBLIC) )
            return self::vPublic();
        else if ( $token->is(Token::T_PROTECTED) )
            return self::vProtected();
        else if ( $token->is(Token::T_PRIVATE) )
            return self::vPrivate();

        throw new \r8\Exception\Argument(0, "Token", "Invalid token source");
    }

}

