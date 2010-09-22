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
class test_classes_App_Scanner extends \vc\Test\TestCase
{

    public function testScan ()
    {
        $file = new \r8\FileSys\File(__FILE__);
        $result = new \vc\Data\File(__FILE__);

        $parser = $this->getMock('\vc\iface\Parser');
        $parser->expects( $this->once() )->method( "parse" )
            ->with( $this->equalTo( $file ) )
            ->will( $this->returnValue( $result ) );

        $storage = $this->getMock('\vc\iface\Storage');
        $storage->expects( $this->once() )->method( "store" )
            ->with( $this->equalTo( $result ) );

        $scanner = new \vc\App\Scanner(
            $this->getParseLogger(), $parser, $storage
        );

        $scanner->scan(
            \r8(new \vc\App\Paths)->addInput($file)
        );
    }

    public function testScan_ParseError ()
    {
        $file = new \r8\FileSys\File(__FILE__);

        $parser = $this->getMock('\vc\iface\Parser');
        $parser->expects( $this->once() )->method( "parse" )
            ->with( $this->equalTo( $file ) )
            ->will( $this->throwException(
                $this->getMock('\vc\Tokens\Exception')
            ));

        $storage = $this->getMock('\vc\iface\Storage');
        $storage->expects( $this->never() )->method( "store" );

        $scanner = new \vc\App\Scanner(
            $this->getParseLogger(), $parser, $storage
        );

        $scanner->scan(
            \r8(new \vc\App\Paths)->addInput($file)
        );
    }

}

?>