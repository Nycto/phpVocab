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
 * A namespace alias defined with the "use" clause
 */
class Alias
{

    /**
     * The namespace path being aliased
     *
     * @var string
     */
    private $path;

    /**
     * The alias, if given
     *
     * @var String
     */
    private $alias;

    /**
     * Constructor...
     *
     * @param String $path The path being aliased
     */
    public function __construct ( $path )
    {
        $this->path = (string) $path;
    }

    /**
     * Returns the Path being aliased
     *
     * @return String
     */
    public function getPath ()
    {
        return $this->path;
    }

    /**
     * Sets the Aliased value
     *
     * @param String $alias
     * @return \vc\Data\Alias Returns a self reference
     */
    public function setAlias ( $alias )
    {
        $this->alias = (string) $alias ?: NULL;
        return $this;
    }

    /**
     * Returns the Alias, if given
     *
     * @return String|NULL
     */
    public function getAlias ()
    {
        if ( !isset($this->alias) )
            $this->alias = \r8\ary\last( explode('\\', $this->path) );

        return $this->alias;
    }

}

