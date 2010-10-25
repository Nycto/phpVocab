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
     * Whether this argument is passed by reference
     *
     * @var Boolean
     */
    private $reference = FALSE;

    /**
     * Sets the Variable name of this argument
     *
     * @param String $variable
     * @return \vc\Data\Arg Returns a self reference
     */
    public function setVariable ( $variable )
    {
        $this->variable = (string) $variable;
        return $this;
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
     * Sets the type hinting associated with this argument
     *
     * @param String $type
     * @return \vc\Data\Arg Returns a self reference
     */
    public function setType ( $type )
    {
        $this->type = (string) $type ?: NULL;
        return $this;
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
     * Sets the default value for this argument
     *
     * @param \vc\Data\Value $default
     * @return \vc\Data\Arg Returns a self reference
     */
    public function setDefault ( \vc\Data\Value $default )
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Returns the Default value for this variable, if any
     *
     * @return \vc\Data\Value
     */
    public function getDefault ()
    {
        return $this->default;
    }

    /**
     * Sets the whether this argument is passed by reference
     *
     * @param Boolean $reference
     * @return \vc\Data\Arg Returns a self reference
     */
    public function setReference ( $reference )
    {
        $this->reference = (bool) $reference;
        return $this;
    }

    /**
     * Returns the whether this argument is passed by reference
     *
     * @return Boolean
     */
    public function getReference ()
    {
        return $this->reference;
    }

}

