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
class Tag
{

    /**
     * The name of this tag
     *
     * @var String
     */
    private $name;

    /**
     * The content of this tag
     *
     * @var String
     */
    private $content;

    /**
     * Constructor...
     *
     * @param String $name The name of this tag
     * @param String $content The content of this tag
     */
    public function __construct ( $name, $content )
    {
        $this->name = (string) $name;
        $this->content = (string) $content;
    }

    /**
     * Returns the Name of this tag
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Returns the Content of this tag
     *
     * @return String
     */
    public function getContent ()
    {
        return $this->content;
    }

}

?>