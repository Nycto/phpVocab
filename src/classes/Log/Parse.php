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

namespace vc\Log;

/**
 * A logger targetted at the parsing phase
 */
class Parse
{

    /**
     * The logger to report back to
     *
     * @var \r8\Log
     */
    private $log;

    /**
     * Constructor...
     *
     * @param \r8\Log $log The logger to report back to
     */
    public function __construct ( \r8\Log $log )
    {
        $this->log = $log;
    }

    /**
     * Logs that a new file is being parsed
     *
     * @param \r8\FileSys\File $file The file being parsed
     * @return NULL
     */
    public function parsingFile ( \r8\FileSys\File $file )
    {
        $this->log->info(
            sprintf('Parsing File: %s', $file->getPath()),
            'PARSE_FILE'
        );
    }

    /**
     * Logs that a file could not be parsed
     *
     * @param \r8\FileSys\File $file The file that was parsed
     * @param \vc\Tokens\Exception $err The error that occurred
     * @return NULL
     */
    public function errorParsingFile (
        \r8\FileSys\File $file,
        \vc\Tokens\Exception $err
    ) {
        $this->log->warning(
            sprintf(
                'Unable to parse file (%s) becase of error: %s',
                $file->getPath(),
                $err->getMessage()
            ),
            'PARSE_ERR'
        );
    }

}

