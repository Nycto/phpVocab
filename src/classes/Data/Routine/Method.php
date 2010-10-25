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

namespace vc\Data\Routine;

/**
 * A method attached to a class
 */
class Method extends \vc\Data\Routine
{

    /**
     * Whether this method is flagged as final
     *
     * @var Boolean
     */
    private $final = FALSE;

    /**
     * Whether this method is static
     *
     * @var Boolean
     */
    private $static = FALSE;

    /**
     * Whether this method is abstract
     *
     * @var Boolean
     */
    private $abstract = FALSE;

    /**
     * The visibility of this method
     *
     * @var \vc\Data\Visibility
     */
    private $visibility;

    /**
     * Constructor...
     *
     * @param Integer $line The line this routine starts on
     * @param \vc\Data\Comment $comment The comment describing this routine
     */
    public function __construct ( $line, \vc\Data\Comment $comment = NULL )
    {
        parent::__construct( $line, $comment );
        $this->visibility = \vc\Data\Visibility::vPUBLIC();
    }

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
     * Sets whether this method is static
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
     * Returns whether this method is static
     *
     * @return Boolean
     */
    public function getStatic ()
    {
        return $this->static;
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
     * Sets the visibility of this method
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
     * Returns the visibility of this method
     *
     * @return \vc\Data\Visibility
     */
    public function getVisibility ()
    {
        return $this->visibility;
    }

}

