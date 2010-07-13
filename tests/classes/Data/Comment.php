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
class test_classes_Data_Comment extends PHPUnit_Framework_TestCase
{

    public function testConstructAccessors ()
    {
        $comment = new \vc\Data\Comment("text");
        $this->assertSame( "text", $comment->getText() );

        $comment = new \vc\Data\Comment;
        $this->assertNull( $comment->getText() );
    }

    public function testTags ()
    {
        $comment = new \vc\Data\Comment;
        $this->assertSame( array(), $comment->getTags() );

        $tag1 = new \vc\Data\Tag("note", "details");
        $this->assertSame( $comment, $comment->addTag($tag1) );
        $this->assertSame( array($tag1), $comment->getTags() );

        $tag2 = new \vc\Data\Tag("note", "details");
        $this->assertSame( $comment, $comment->addTag($tag2) );
        $this->assertSame( array($tag1, $tag2), $comment->getTags() );
    }

}

?>