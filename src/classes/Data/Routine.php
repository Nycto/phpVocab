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
 * The base class for a function or method call
 */
abstract class Routine
{

    /**
     * The line this routine starts on
     *
     * @var Integer
     */
    private $line;

    /**
     * The name of this routine
     *
     * @var String
     */
    private $name;

    /**
     * The comment associated with this routine
     *
     * @var \vc\Data\Comment
     */
    private $comment;

    /**
     * The argument list for this routine
     *
     * @var Array An array of \vc\Data\Arg objects
     */
    private $args = array();

    /**
     * Constructor...
     *
     * @param Integer $line The line this routine starts on
     * @param \vc\Data\Comment $comment The comment describing this routine
     */
    public function __construct ( $line, \vc\Data\Comment $comment = NULL )
    {
        $this->line = (int) $line;
        $this->comment = $comment ?: new \vc\Data\Comment;
    }

    /**
     * Returns the Line this routine starts on
     *
     * @return Integer
     */
    public function getLine ()
    {
        return $this->line;
    }

    /**
     * Returns the Comment describing this routine
     *
     * @return \vc\Data\Comment
     */
    public function getComment ()
    {
        return $this->comment;
    }

    /**
     * Sets the Name of this routine
     *
     * @param String $name
     * @return \vc\Data\routine Returns a self reference
     */
    public function setName ( $name )
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the Name of this routine
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Adds a new argument to this routine
     *
     * @param \vc\Data\Arg $arg
     * @return \vc\Data\Routine Returns a self reference
     */
    public function addArg ( \vc\Data\Arg $arg )
    {
        $this->args[] = $arg;
        return $this;
    }

    /**
     * Returns the Args associated with this routine
     *
     * @return Array An array of \vc\Data\Arg objects
     */
    public function getArgs ()
    {
        return $this->args;
    }

}

?>