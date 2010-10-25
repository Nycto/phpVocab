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
 * A Constant
 */
class Constant
{

    /**
     * The name of this constant
     *
     * @var String
     */
    private $name;

    /**
     * The value of this constant
     *
     * @var String
     */
    private $value;

    /**
     * Constructor...
     *
     * @param String $name The name of this constant
     */
    public function __construct ( $name )
    {
        $this->name = (string) $name;
    }

    /**
     * Returns the Name of this constant
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Sets the value of this constant
     *
     * @param \vc\Data\Value $value
     * @return \vc\Data\Constant Returns a self reference
     */
    public function setValue ( \vc\Data\Value $value )
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Returns the value of this constant
     *
     * @return String
     */
    public function getValue ()
    {
        return $this->value;
    }

}

