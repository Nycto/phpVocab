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
 * A doc comment
 */
class Comment
{

    /**
     * The text of the comment
     *
     * @var String
     */
    private $text;

    /**
     * The list of tags in this comment
     *
     * @var Array
     */
    private $tags = array();

    /**
     * Constructor...
     *
     * @param String $text The Summary text in this comment
     */
    public function __construct ( $text = NULL )
    {
        $this->text = trim( (string) $text ) ?: NULL;
    }

    /**
     * Returns the text in this comment
     *
     * @return String
     */
    public function getText ()
    {
        return $this->text;
    }

    /**
     * Adds a new tag onto this comment
     *
     * @param \vc\Data\Tag $tag
     * @return \vc\Data\Comment Returns a self reference
     */
    public function addTag ( \vc\Data\Tag $tag )
    {
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * Returns the Tags in this comment
     *
     * @return Array An array of \vc\Data\Tag objects
     */
    public function getTags ()
    {
        return $this->tags;
    }

}

