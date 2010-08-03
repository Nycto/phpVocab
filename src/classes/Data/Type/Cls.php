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
 * A class
 */
class Cls extends \vc\Data\Type
{

    /**
     * Whether this method is flagged as final
     *
     * @var Boolean
     */
    private $final = FALSE;

    /**
     * Whether this method is abstract
     *
     * @var Boolean
     */
    private $abstract = FALSE;

    /**
     * The parent of this class
     *
     * @var String
     */
    private $extends;

    /**
     * The interface this string implements
     *
     * @var Array
     */
    private $implements = array();

    /**
     * The properties of this class
     *
     * @var Array An array of \vc\Data\Property objects
     */
    private $properties = array();

    /**
     * Sets whether this method is final
     *
     * @param Boolean $final
     * @return \vc\Data\Method Returns a self reference
     */
    public function setFinal ( $final )
    {
        $this->final = (bool) $final;
        return $this;
    }

    /**
     * Returns whether this method is final
     *
     * @return Boolean
     */
    public function getFinal ()
    {
        return $this->final;
    }

    /**
     * Sets whether this method is abstract
     *
     * @param Boolean $abstract
     * @return \vc\Data\Method Returns a self reference
     */
    public function setAbstract ( $abstract )
    {
        $this->abstract = (bool) $abstract;
        return $this;
    }

    /**
     * Returns whether this method is abstract
     *
     * @return Boolean
     */
    public function getAbstract ()
    {
        return $this->abstract;
    }

    /**
     * Sets the parent of this class
     *
     * @param String $extends
     * @return \vc\Data\Type\Cls Returns a self reference
     */
    public function setExtends ( $extends )
    {
        $this->extends = $extends;
        return $this;
    }

    /**
     * Returns the parent of this class
     *
     * @return String
     */
    public function getExtends ()
    {
        return $this->extends;
    }

    /**
     * Adds a new interface that this class implements
     *
     * @param String $implements
     * @return \vc\Data\Type\Cls Returns a self reference
     */
    public function addIFace ( $iface )
    {
        $this->implements[] = $iface;
        return $this;
    }

    /**
     * Returns the interfaces this class implements
     *
     * @return Array
     */
    public function getIFaces ()
    {
        return $this->implements;
    }

    /**
     * Adds a property to this class
     *
     * @param \vc\Data\Property $property
     * @return \vc\Data\Type\Cls Returns a self reference
     */
    public function addProperty ( \vc\Data\Property $property )
    {
        $this->properties[] = $property;
        return $this;
    }

    /**
     * Returns the Properties in this class
     *
     * @return Array Returns an array of \vc\Data\Property objects
     */
    public function getProperties ()
    {
        return $this->properties;
    }

}

?>