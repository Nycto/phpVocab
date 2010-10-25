<?php
/**
 * Sets up the phpVocab environment
 *
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

define("VOCAB_VERSION", '0.1.0dev');

define("VOCAB_DIR", rtrim(__DIR__, "/"));

// Round Eights for your library needs
require_once VOCAB_DIR .'/../lib/RoundEights.phar';

// Set up the autoload structure for vocab specific classes
\r8\Autoload::getInstance()
    ->register('vc', VOCAB_DIR .'/classes')
    ->register('vc\iface', VOCAB_DIR .'/ifaces');

