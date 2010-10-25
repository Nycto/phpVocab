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
 * Builds objects related to the command line interface
 */
class CLI
{

    /**
     * Builds an object for parsing the command line arguments
     *
     * @return \r8\CLI\Command
     */
    static public function getArgParser ()
    {
        $cmd = new \r8\CLI\Command(
            'vocab',
            'PHP documentation generator'
        );

        $pathFilter = new \r8\Filter\Chain(
            new \r8\Filter\Printable,
            new \r8\Curry\Call('trim')
        );

        $cmd->addArg(new \r8\CLI\Arg\One(
            'Output Directory',
            $pathFilter,
            new \r8\Validator\All(
                \r8( new \r8\Validator\NotEmpty )
                    ->addError('Output directory must not be empty'),
                new \r8\Validator\Callback(function ($dir) {
                    return is_file($dir)
                        ? 'Path must not be an existing file: '. $dir : NULL;
                })
            )
        ));

        $inputValidator = new \r8\Validator\All(
            \r8( new \r8\Validator\NotEmpty )
                ->addError('Input path must not be empty'),
            new \r8\Validator\Callback(function ($path) {
                return file_exists($path)
                    ? NULL : 'Path does not exist: '. $path;
            })
        );

        $cmd->addArg(new \r8\CLI\Arg\One(
            'Input Path', $pathFilter, $inputValidator
        ));

        $cmd->addArg(new \r8\CLI\Arg\Many(
            'Input Path', $pathFilter, $inputValidator
        ));

        // A command form for viewing help/version info
        $info = new \r8\CLI\Form;
        $cmd->addForm($info);

        $info->addOption(
            \r8( new \r8\CLI\Option('v', 'Outputs version information') )
                ->addFlag('version')
        );

        $info->addOption(
            \r8( new \r8\CLI\Option('h', 'Displays the help screen') )
                ->addFlag('help')
        );

        return $cmd;
    }

}

