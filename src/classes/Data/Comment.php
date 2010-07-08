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
     * The summary text in this comment
     *
     * @var String
     */
    private $summary;

    /**
     * The detailed information in this comment
     *
     * @var String
     */
    private $details;

    /**
     * The list of tags in this comment
     *
     * @var Array
     */
    private $tags = array();

    /**
     * Constructor...
     *
     * @param String $summary The Summary text in this comment
     * @param String $details The detailed information in this comment
     */
    public function __construct ( $summary, $details )
    {
        $this->summary = trim( (string) $summary ) ?: NULL;
        $this->details = trim( (string) $details ) ?: NULL;
    }

    /**
     * Returns the Summary text in this comment
     *
     * @return String
     */
    public function getSummary ()
    {
        return $this->summary;
    }

    /**
     * Returns the detailed information in this comment
     *
     * @return String
     */
    public function getDetails ()
    {
        return $this->details;
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

?>