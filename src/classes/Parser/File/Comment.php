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
 * Parses the comment out of a file
 */
class Comment
{

    /**
     * The parser for reading comments
     *
     * @var \vc\Parser\Comment
     */
    private $comment;

    /**
     * The parser to hand off to
     *
     * @var \vc\Parser\File\NSpaces
     */
    private $nspace;

    /**
     * Constructor...
     *
     * @param \vc\Parser\Comment $comment The parser for reading comments
     * @param \vc\Parser\File\NSpaces $nspace The parser to hand off to
     */
    public function __construct (
        \vc\Parser\Comment $comment,
        \vc\Parser\File\NSpaces $nspace
    ) {
        $this->comment = $comment;
        $this->nspace = $nspace;
    }

    /**
     * Parses the given token reader
     *
     * @param \vc\Data\File $file The file to parse data into
     * @param \vc\Tokens\Access $access
     * @return NULL
     */
    public function parse ( \vc\Data\File $file, \vc\Tokens\Access $access )
    {
        try {
            // First up, we look for the comment
            $token = $access->findRequired(
                array( Token::T_DOC_COMMENT ),
                array( Token::T_OPEN_TAG )
            );

            $comment = $this->comment->parse( $token->getContent() );
        }

        // This will be thrown if an interrupt token was encountered before
        // a comment was found
        catch ( \vc\Tokens\Exception\UnexpectedToken $err ) {
            $comment = new \vc\Data\Comment;
        }

        $file->setComment( $comment );

        $this->nspace->parse( $file, $access );
    }

}


