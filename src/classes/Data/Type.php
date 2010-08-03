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
 * The base class for classes and interfaces
 */
abstract class Type
{

    /**
     * The line this type starts on
     *
     * @var Integer
     */
    private $line;

    /**
     * The name of this type
     *
     * @var String
     */
    private $name;

    /**
     * The comment associated with this type
     *
     * @var \vc\Data\Comment
     */
    private $comment;

    /**
     * The list of methods in this type
     *
     * @var Array An array of \vc\Data\Routine\Method objects
     */
    private $methods = array();

    /**
     * The list of constants loaded in this type
     *
     * Betcha didn't know that interfaces could have constants? I didn't.
     *
     * @var Array An array of \vc\Data\Constant objects
     */
    private $constants = array();

    /**
     * Constructor...
     *
     * @param Integer $line The line this type starts on
     * @param \vc\Data\Comment $comment The comment describing this type
     */
    public function __construct ( $line, \vc\Data\Comment $comment = NULL )
    {
        $this->line = (int) $line;
        $this->comment = $comment ?: new \vc\Data\Comment;
    }

    /**
     * Returns the Line this type starts on
     *
     * @return Integer
     */
    public function getLine ()
    {
        return $this->line;
    }

    /**
     * Returns the Comment describing this type
     *
     * @return \vc\Data\Comment
     */
    public function getComment ()
    {
        return $this->comment;
    }

    /**
     * Sets the Name of this type
     *
     * @param String $name
     * @return \vc\Data\Type Returns a self reference
     */
    public function setName ( $name )
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the Name of this type
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Adds a method to this type
     *
     * @param \vc\Data\Routine\Method $method
     * @return \vc\Data\Type Returns a self reference
     */
    public function addMethod ( \vc\Data\Routine\Method $method )
    {
        $this->methods[] = $method;
        return $this;
    }

    /**
     * Returns the Methods loaded in this type
     *
     * @return Array An array of \vc\Data\Routine\Method objects
     */
    public function getMethods ()
    {
        return $this->methods;
    }

    /**
     * Adds a Constant to this type
     *
     * @param \vc\Data\Constant $constant
     * @return \vc\Data\Type Returns a self reference
     */
    public function addConstant ( \vc\Data\Constant $constant )
    {
        $this->constants[] = $constant;
        return $this;
    }

    /**
     * Returns the Constants loaded in this type
     *
     * @return Array Returns an array of \vc\Data\Constant objects
     */
    public function getConstants ()
    {
        return $this->constants;
    }

}

?>