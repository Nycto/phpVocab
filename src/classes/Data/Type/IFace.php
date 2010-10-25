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

namespace vc\Data\Type;

/**
 * An interface
 */
class IFace extends \vc\Data\Type
{

    /**
     * The list of interfaces this type extends
     *
     * @var array
     */
    private $extends = array();

    /**
     * Sets the path references that this interface extends
     *
     * @param Array $extends The list of paths this interface extends
     * @return \vc\Data\Type\IFace Returns a self reference
     */
    public function setExtends ( array $extends )
    {
        $this->extends = array_filter( array_map('strval', $extends) );
        return $this;
    }

    /**
     * Returns the list of references this interface extends
     *
     * @return Array
     */
    public function getExtends ()
    {
        return $this->extends;
    }

}

