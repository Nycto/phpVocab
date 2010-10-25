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

namespace vc\Provider;

/**
 * Provides access to the token parsers
 */
class Parser
{

    /**
     * Returns a parser for reading a file into an object structure
     *
     * @return \vc\Parser\File\Path
     */
    static public function getFileParser ()
    {
        $brackets = new \vc\Parser\Brackets;
        $path = new \vc\Parser\Path;
        $value = new \vc\Parser\Value( $brackets, $path );
        $constant = new \vc\Parser\Constant($value);
        $pathList = new \vc\Parser\PathList($path);
        $routine = new \vc\Parser\Routine\Body(
            new \vc\Parser\Routine\Args( $path, $value ),
            $brackets
        );
        $method = new \vc\Parser\Routine\Method( $routine );

        return new \vc\Parser\File\Path(
            new \vc\Parser\File\Comment(
                new \vc\Parser\Comment,
                new \vc\Parser\File\NSpaces(
                    $path,
                    new \vc\Parser\NSpace\Body(
                        new \vc\Parser\NSpace\Alias( $path ),
                        $constant,
                        new \vc\Parser\Routine\Func( $routine ),
                        new \vc\Parser\Object\Header(
                            $path,
                            $pathList,
                            new \vc\Parser\Object\Members(
                                $constant,
                                new \vc\Parser\Object\Signature(
                                    new \vc\Parser\Object\Property( $value ),
                                    $method
                                )
                            )
                        ),
                        new \vc\Parser\IFace\Header(
                            $pathList,
                            new \vc\Parser\IFace\Members(
                                $constant,
                                $method
                            )
                        )
                    )
                )
            )
        );
    }

}

