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
 * Provides access to the configuration data for the current scan
 */
class Config
{

    /**
     * The output directory
     *
     * @var \r8\FileSys\Dir
     */
    private $output;

    /**
     * The input files to read
     *
     * @var \vc\App\Paths
     */
    private $paths;

    /**
     * Constructor...
     *
     * @param \r8\FileSys\Dir $output The output directory
     * @param \vc\App\Paths $paths The input files to read
     */
    public function __construct (
        \r8\FileSys\Dir $output,
        \vc\App\Paths $paths
    ) {
        $this->output = $output;
        $this->paths = $paths;
    }

    /**
     * Returns the Output Directory to put the results into
     *
     * @return \r8\FileSys\Dir
     */
    public function getOutputDir ()
    {
        return $this->output;
    }

    /**
     * Returns the Input Paths to parse
     *
     * @return \Iterator
     */
    public function getInputPaths ()
    {
        return $this->paths;
    }

}

?>