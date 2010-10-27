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

/**
 * Parses a file path
 */
class Path implements \vc\iface\Parser
{

    /**
     * The file comment parser
     *
     * @var \vc\Parser\File\Comment
     */
    private $comment;

    /**
     * Constructor...
     *
     * @param \vc\Parser\File\Comment $comment The file comment parser
     */
    public function __construct ( \vc\Parser\File\Comment $comment )
    {
        $this->comment = $comment;
    }

    /**
     * Parses the given token reader
     *
     * @param \r8\FileSys\File $path The file to parse
     * @return \vc\Data\File
     */
    public function parse ( \r8\FileSys\File $path )
    {
        $file = new \vc\Data\File( $path->getPath() );
        $tokens = \vc\Tokens\Access::buildAccess(
            new \vc\Tokens\Parser( new \r8\Stream\In\File( $path ) )
        );

        $this->comment->parse( $file, $tokens );

        return $file;
    }

}
