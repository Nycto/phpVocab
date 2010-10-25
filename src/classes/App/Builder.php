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

namespace vc\App;

/**
 * Builds a Configuration given the command line input
 */
class Builder
{

    /**
     * Builds the configuration
     *
     * @return \vc\App\Config
     */
    public function build ( \r8\CLI\Result $cli )
    {
        if ( $cli->countArgs() < 2 ) {
            throw new \r8\Exception\Argument(
                0, 'CLI Args', 'Less than two arguments given'
            );
        }

        $args = $cli->getArgs();

        $output = new \r8\FileSys\Dir( array_shift( $args ) );

        $paths = new \vc\App\Paths;
        foreach ( $args as $path ) {
            $paths->addInput( \r8\FileSys::create($path) );
        }

        return new \vc\App\Config( $output, $paths );
    }

}

