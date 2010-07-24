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
class test_classes_Parser_File_Comment extends \vc\Test\TestCase
{

    public function testCommentFound ()
    {
        $reader = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenAnOpenTag()->thenSomeSpace()
                ->thenADocComment('This is a file comment')
        );

        $parser = new \vc\Parser\File\Comment(
            new \vc\Parser\Comment
        );

        $file = new \vc\Data\File('file.php');

        $parser->parse( $file, $reader );

        $this->assertEquals(
            new \vc\Data\Comment('This is a file comment'),
            $file->getComment()
        );
    }

    public function testNoCommentFound ()
    {
        $reader = new \vc\Tokens\Search(
            $this->oneTokenReader()->thenAnOpenTag()->thenSomeSpace()
                ->thenAClass()->thenADocComment('This is a file comment')
        );

        $parser = new \vc\Parser\File\Comment(
            new \vc\Parser\Comment
        );

        $file = new \vc\Data\File('file.php');

        $parser->parse( $file, $reader );

        $this->assertEquals( new \vc\Data\Comment, $file->getComment() );
    }

}

?>