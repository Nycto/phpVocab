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
 * Unit test for running all the tests
 */
class test_classes_Parser_Comment extends \vc\Test\TestCase
{

    public function testParse_SingleLine ()
    {
        $parser = new \vc\Parser\Comment;

        $this->assertEquals(
            new \vc\Data\Comment('This is a test comment'),
            $parser->parse(
                '/**
                  * This is a test comment
                  */'
            )
        );
    }

    public function testParse_MultiLine ()
    {
        $parser = new \vc\Parser\Comment;
        $this->assertEquals(
            new \vc\Data\Comment(
                "This is a test comment\n\nAnother line"
            ),
            $parser->parse(
                '/**
                  * This is a test comment
                  *
                  * Another line
                  *
                  */'
            )
        );
    }

    public function testParse_WithTags ()
    {
        $parser = new \vc\Parser\Comment;
        $this->assertEquals(
            \r8( new \vc\Data\Comment("This is a test comment") )
                ->addTag( new \vc\Data\Tag("tag", "Single Line") )
                ->addTag( new \vc\Data\Tag("note", "Line 1\nLine 2") )
                ->addTag( new \vc\Data\Tag("flag", NULL) ),
            $parser->parse(
                '/**
                  * This is a test comment
                  *
                  * @tag Single Line
                  * @note Line 1
                  *     Line 2
                  * @flag
                  */'
            )
        );
    }

    public function testParse_TagsNestedAtSymbol ()
    {
        $parser = new \vc\Parser\Comment;
        $this->assertEquals(
            \r8( new \vc\Data\Comment("This is a test comment") )
                ->addTag( new \vc\Data\Tag("tag", "Single @Line") )
                ->addTag( new \vc\Data\Tag("flag", NULL) ),
            $parser->parse(
                '/**
                  * This is a test comment
                  * @tag Single @Line
                  * @flag
                  */'
            )
        );
    }

}

