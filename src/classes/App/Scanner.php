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

use \vc\iface\Parser;
use \vc\iface\Storage;

/**
 * Scans through a set of inputs and saves them to a storage container
 */
class Scanner
{

    /**
     * The file parser
     *
     * @var \vc\iface\Parser
     */
    private $parser;

    /**
     * The storage mechanism
     *
     * @var \vc\iface\Storage
     */
    private $storage;

    /**
     * Constructor...
     *
     * @param \vc\iface\Parser $parser The file parser
     * @param \vc\iface\Storage $storage the storage mechanism
     */
    public function __construct ( Parser $parser, Storage $storage )
    {
        $this->parser = $parser;
        $this->storage = $storage;
    }

    /**
     * Scans through the given file list
     *
     * @param \vc\App\Paths $pathList The list of paths to parse
     * @return NULL
     */
    public function scan ( \vc\App\Paths $pathList )
    {
        foreach ( $pathList as $path ) {

            $this->storage->store(
                $this->parser->parse( $path )
            );
        }
    }

}

?>