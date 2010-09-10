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

require_once rtrim( __DIR__, "/" ) ."/../../../setup.php";

/**
 * Unit test for running all the tests
 */
class test_classes_Parser_File_Path extends \vc\Test\TestCase
{

    public function testParse ()
    {
        $commentParser = $this->getStub('\vc\Parser\File\Comment');
        $commentParser->expects( $this->once() )->method( "parse" )
            ->with(
                $this->isInstanceOf('\vc\Data\File'),
                $this->isInstanceOf('\vc\Tokens\Access')
            );

        $parser = new \vc\Parser\File\Path( $commentParser );

        $result = $parser->parse( new \r8\FileSys\File(__FILE__) );

        $this->assertThat( $result, $this->isInstanceOf( '\vc\Data\File' ) );
    }

}

?>