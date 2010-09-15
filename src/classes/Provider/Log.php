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
 * Provides access to the logger
 */
class Log
{

    /**
     * Once built, the shared logger
     *
     * @var \r8\Log
     */
    static private $log;

    /**
     * Returns the shared logger
     *
     * @return \r8\Log
     */
    static public function getLog ()
    {
        if ( !isset(self::$log) ) {
            self::$log = new \r8\Log(
                new \r8\Log\Node\Ternary(
                    new \r8\Log\Matcher\Level(array(
                        \r8\Log\Level::DEBUG, \r8\Log\Level::ALERT,
                        \r8\Log\Level::INFO, \r8\Log\Level::NOTICE,
                    )),
                    new \r8\Log\Node\Stream(
                        new \r8\Stream\Out\StdOut
                    ),
                    new \r8\Log\Node\Stream(
                        new \r8\Stream\Out\StdErr
                    )
                )
            );
        }
        return self::$log;
    }

    /**
     * Returns a Logger specialized for the parsing phase
     *
     * @return \vc\Log\Parse
     */
    static public function getParseLogger ()
    {
        return new \vc\Log\Parse( self::getLog() );
    }

}

?>