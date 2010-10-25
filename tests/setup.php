<?php
/**
 * Unit test configuration file
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

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

define("r8_SUPPRESS_HANDLERS", TRUE);
define("vocab_TEST_DATA", __DIR__ .'/Data');

require_once rtrim( __DIR__, "/" ) ."/../src/include.php";

// Set up the autoload structure for vocab specific classes
\r8\Autoload::getInstance()
    ->register('vc\Test', rtrim( __DIR__, "/" ) .'/Test');

error_reporting( E_ALL | E_STRICT );

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');
PHPUnit_Util_Filter::addDirectoryToFilter(rtrim( __DIR__, "/" ) .'/Test');

