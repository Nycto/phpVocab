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
 * A namespace
 */
class NSpace
{

    /**
     * The path of this namespace
     *
     * @var String
     */
    private $path;

    /**
     * The list of aliases in this file
     *
     * @var Array An array of \vc\Data\Alias objects
     */
    private $aliases = array();

    /**
     * The list of constants in this namespace
     *
     * @var Array
     */
    private $constants = array();

    /**
     * The list of functions in this namespace
     *
     * @var Array
     */
    private $functions = array();

    /**
     * The list of interfaces or classes in this namespace
     *
     * @var Array
     */
    private $types = array();

    /**
     * Constructor...
     *
     * @param String $path The path to this namespace
     */
    public function __construct ( $path )
    {
        $this->path = trim( (string) $path );
    }

    /**
     * Returns the Path of this namespace
     *
     * @return String
     */
    public function getPath ()
    {
        return $this->path;
    }

    /**
     * Adds a new alias to this file
     *
     * @param \vc\Data\Alias $alias
     * @return \vc\Data\File Returns a self reference
     */
    public function addAlias ( \vc\Data\Alias $alias )
    {
        $this->aliases[] = $alias;
        return $this;
    }

    /**
     * Returns the Aliases loaded in this file
     *
     * @return Array An array of \vc\Data\Alias objects
     */
    public function getAliases ()
    {
        return $this->aliases;
    }

    /**
     * Adds a constant to this namespace
     *
     * @param \vc\Data\Constant $constant
     * @return \vc\Data\NSpace Returns a self reference
     */
    public function addConstant ( \vc\Data\Constant $constant )
    {
        $this->constants[] = $constant;
        return $this;
    }

    /**
     * Returns the Constants loaded into this namespace
     *
     * @return Array An array of \vc\Data\Constant objects
     */
    public function getConstants ()
    {
        return $this->constants;
    }

    /**
     * Adds a function to this namespace
     *
     * @param \vc\Data\Routine\Func $function
     * @return \vc\Data\NSpace Returns a self reference
     */
    public function addFunction ( \vc\Data\Routine\Func $function )
    {
        $this->functions[] = $function;
        return $this;
    }

    /**
     * Returns the Functions loaded in this namespace
     *
     * @return Array An array of \vc\Data\Routine\Func objects
     */
    public function getFunctions ()
    {
        return $this->functions;
    }

    /**
     * Adds a class or an interface to this namespace
     *
     * @param \vc\Data\Type $type
     * @return \vc\Data\NSpace Returns a self reference
     */
    public function addType ( \vc\Data\Type $type )
    {
        $this->types[] = $type;
        return $this;
    }

    /**
     * Returns the Types in this namespace
     *
     * @return Array An array of \vc\Data\Type objects
     */
    public function getTypes ()
    {
        return $this->types;
    }

}

?>