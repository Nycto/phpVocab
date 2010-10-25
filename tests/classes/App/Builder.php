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

require_once rtrim( __DIR__, "/" ) ."/../../setup.php";

/**
 * Unit test
 */
class test_classes_App_Builder extends \r8\Test\TestCase\Dir
{

    /**
     * Returns a test CLI result with arguments
     *
     * @return \r8\CLI\Result
     */
    public function getCLIResult ()
    {
        return \r8( new \r8\CLI\Result )
            ->addArgs(array( $this->dir .'/output' ))
            ->addArgs(array( $this->dir .'/one', $this->dir .'/two' ));
    }

    public function testInsufficientArguments ()
    {
        $builder = new \vc\App\Builder;

        try {
            $builder->build( new \r8\CLI\Result );

            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {}
    }

    public function testInputOutputPaths ()
    {
        $builder = new \vc\App\Builder;

        $result = $builder->build( $this->getCLIResult() );
        $this->assertThat( $result, $this->isInstanceOf( '\vc\App\Config' ) );

        $this->assertThat(
            $result->getOutputDir(),
            $this->isInstanceOf( '\r8\FileSys\Dir' )
        );

        $this->assertThat(
            $result->getInputPaths(),
            $this->isInstanceOf( '\vc\App\Paths' )
        );
        \r8\Test\Constraint\Iterator::assertCount( 2, $result->getInputPaths() );
    }

}

