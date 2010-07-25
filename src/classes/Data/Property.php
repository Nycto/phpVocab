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
 * A property attached to a class
 */
class Property
{

    /**
     * The line this property starts on
     *
     * @var Integer
     */
    private $line;

    /**
     * The comment associated with this property
     *
     * @var \vc\Data\Comment
     */
    private $comment;

    /**
     * The name of this property
     *
     * @var String
     */
    private $name;

    /**
     * The visibility of this signature
     *
     * @var \vc\Data\Visibility
     */
    private $visibility;

    /**
     * Whether this signature is static
     *
     * @var Boolean
     */
    private $static = FALSE;

    /**
     * Builds a new method using a signature as the source
     *
     * @param \vc\Data\Signature $signature
     * @return \vc\Data\Property
     */
    static public function build ( \vc\Data\Signature $signature )
    {
        $method = new self( $signature->getLine(), $signature->getComment() );
        $method->setStatic( $signature->getStatic() );
        $method->setVisibility( $signature->getVisibility() );
        return $method;
    }

    /**
     * Constructor...
     *
     * @param Integer $line The line this property starts on
     * @param \vc\Data\Comment $comment The comment describing this property
     */
    public function __construct ( $line, \vc\Data\Comment $comment = NULL )
    {
        $this->line = (int) $line;
        $this->comment = $comment;
        $this->visibility = \vc\Data\Visibility::vPublic();
    }

    /**
     * Returns the Line this property starts on
     *
     * @return Integer
     */
    public function getLine ()
    {
        return $this->line;
    }

    /**
     * Returns the Comment describing this property
     *
     * @return \vc\Data\Comment
     */
    public function getComment ()
    {
        return $this->comment;
    }

    /**
     * Sets the Name of this property
     *
     * @param String $name
     * @return \vc\Data\property Returns a self reference
     */
    public function setName ( $name )
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the Name of this property
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Sets the visibility of this property
     *
     * @param \vc\Data\Visibility $visibility
     * @return \vc\Data\Method Returns a self reference
     */
    public function setVisibility ( \vc\Data\Visibility $visibility )
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * Returns the visibility of this property
     *
     * @return \vc\Data\Visibility
     */
    public function getVisibility ()
    {
        return $this->visibility;
    }

    /**
     * Sets whether this property is static
     *
     * @param Boolean $static
     * @return \vc\Data\Method Returns a self reference
     */
    public function setStatic ( $static )
    {
        $this->static = (bool) $static;
        return $this;
    }

    /**
     * Returns whether this property is static
     *
     * @return Boolean
     */
    public function getStatic ()
    {
        return $this->static;
    }

}

?>