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
class test_classes_Data_File extends \vc\Test\TestCase
{

    public function testConstruct ()
    {
        $file = new \vc\Data\File('path');
        $this->assertSame( 'path', $file->getPath() );
    }

    public function testCommentAccess ()
    {
        $file = new \vc\Data\File('path');
        $this->assertNull( $file->getComment() );

        $comment = new \vc\Data\Comment;
        $this->assertSame( $file, $file->setComment($comment) );
        $this->assertSame( $comment, $file->getComment() );
    }

    public function testNamespaceAccess ()
    {
        $file = new \vc\Data\File('path');
        $this->assertSame( array(), $file->getNamespaces() );

        $nspace1 = new \vc\Data\NSpace;
        $this->assertSame( $file, $file->addNamespace($nspace1) );
        $this->assertSame( array($nspace1), $file->getNamespaces() );

        $nspace2 = new \vc\Data\NSpace;
        $this->assertSame( $file, $file->addNamespace($nspace2) );
        $this->assertSame( array($nspace1, $nspace2), $file->getNamespaces() );
    }

}

?>