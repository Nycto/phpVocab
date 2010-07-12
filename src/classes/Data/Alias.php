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
     * @param String $name The namespace path being aliased
     * @param String $value The alias, if given
     */
    public function __construct ( $path, $alias = NULL )
    {
        $this->path = (string) $path;
        $this->alias = (string) $alias ?: NULL;
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
     * Returns the Alias, if given
     *
     * @return String|NULL
     */
    public function getAlias ()
    {
        return $this->alias;
    }

}

?>