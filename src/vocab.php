<?php
/**
 * Command-line entry-point for phpVocab
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

// get the needed environment
require_once __DIR__ .'/include.php';

$parser = \vc\Provider\CLI::getArgParser();

try {
    $result = $parser->process();
}
catch ( \r8\Exception\Data $err ) {
    echo "Error!\n"
        .$err->getMessage() ."\n"
        ."For details about using this command, use the '--help' option\n\n";
    exit;
}

if ( $result->flagExists('v') ) {
    echo "PHP Vocab, version ". VOCAB_VERSION ."\n\n";
    exit;
}
else if ( $result->flagExists('h') || $result->countArgs() == 0 ) {
    echo $parser->getHelp();
    exit;
}

$config = \r8(new \vc\Input\Builder)->build( $result );

?>