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
 * An individual file
 */
class File
{

    /**
     * The path of this namespace
     *
     * @var String
     */
    private $path;

    /**
     * The file level doc comment
     *
     * @var \vc\Data\Comment
     */
    private $comment;

    /**
     * The list of aliases in this file
     *
     * @var Array An array of \vc\Data\Alias objects
     */
    private $aliases = array();

    /**
     * The namespaces in this file
     *
     * @var Array An array of \vc\Data\NSpace objects
     */
    private $namespaces = array();

    /**
     * Constructor...
     *
     * @param String $path The path to this file
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
     * Sets the Comment for this file
     *
     * @param \vc\Data\Comment $comment
     * @return \vc\Data\File Returns a self reference
     */
    public function setComment ( \vc\Data\Comment $comment )
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Returns the Comment for this file
     *
     * @return \vc\Data\Comment
     */
    public function getComment ()
    {
        return $this->comment;
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
     * Adds a Namespace to this file
     *
     * @param \vc\Data\NSpace $namespace
     * @return \vc\Data\File Returns a self reference
     */
    public function addNamespace ( \vc\Data\NSpace $namespace )
    {
        $this->namespaces[] = $namespace;
        return $this;
    }

    /**
     * Returns the Namespaces loaded in this file
     *
     * @return Array Returns an array of \vc\Data\NSpace objects
     */
    public function getNamespaces ()
    {
        return $this->namespaces;
    }

}

?>