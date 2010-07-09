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

namespace vc\Data;

/**
 * A metho argument
 */
class Arg
{

    /**
     * The variable name of this argument
     *
     * @var String
     */
    private $variable;

    /**
     * If specified, the type hinting for this argument
     *
     * @var String
     */
    private $type;

    /**
     * If specified, the default value for this argument
     *
     * @var String
     */
    private $default;

    /**
     * Constructor...
     *
     * @param String $variable The variable name of this argument
     * @param String $type If specified, the type hinting for this argument
     * @param String $default If specified, the default value for this argument
     */
    public function __construct ( $variable, $type = NULL, $default = NULL )
    {
        $this->variable = (string) $variable;
        $this->type = $type === NULL ? NULL : (string) $type;
        $this->default = $default === NULL ? NULL : (string) $default;
    }

    /**
     * Returns the Variable name of this argument
     *
     * @return String
     */
    public function getVariable ()
    {
        return $this->variable;
    }

    /**
     * Returns the Type hinting for this variable, if any
     *
     * @return String
     */
    public function getType ()
    {
        return $this->type;
    }

    /**
     * Returns the Default value for this variable, if any
     *
     * @return String
     */
    public function getDefault ()
    {
        return $this->default;
    }

}

?>