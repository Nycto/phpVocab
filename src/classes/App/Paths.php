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

namespace vc\App;

/**
 * Collects the list of inputs and provides an iterator over them
 */
class Paths implements \Iterator
{

    /**
     * The list of inputs to read
     *
     * @var \AppendIterator
     */
    private $inputs;

    /**
     * The current iterator offset
     *
     * @var Integer
     */
    private $offset = 0;

    /**
     * Constructor...
     */
    public function __construct ()
    {
        $this->inputs = new \AppendIterator;
    }

    /**
     * Adds an input path
     *
     * @param \r8\FileSys $input
     * @return \vc\App\Paths Returns a self reference
     */
    public function addInput ( \r8\FileSys $input )
    {
        $input = clone $input;

        if ( $input->isFile() ) {
            $this->inputs->append( new \ArrayIterator(array($input)) );
        }
        else if ( $input->isDir() ) {
            $input->setIncludeDots(FALSE);
            $this->inputs->append(
                new \RecursiveIteratorIterator($input)
            );
        }
        else {
            throw new \r8\Exception\Argument(0, 'Input', 'Path does not exist');
        }

        return $this;
    }

    /**
     * Rewinds this iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        $this->inputs->rewind();
        $this->offset = 0;
    }

    /**
     * Returns whether the current iterator value is valid
     *
     * @return boolean
     */
    public function valid ()
    {
        return $this->inputs->valid();
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {
        $this->inputs->next();
        $this->offset++;
    }

    /**
     * Returns the key for the current value
     *
     * @return Integer
     */
    public function key ()
    {
        return $this->offset;
    }

    /**
     * Returns the current iterator value
     *
     * @return Mixed
     */
    public function current ()
    {
        return $this->inputs->current();
    }

}

