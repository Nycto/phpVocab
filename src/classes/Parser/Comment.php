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

namespace vc\Parser;

/**
 * Parses a comment
 */
class Comment
{

    /**
     * A helper method that takes two matched tag arrays and returns a tag
     * object
     *
     * @param String $content The content from the entire comment
     * @param Array $tag1 The tag to build
     * @param Array $tag2 The next tag (this is needed to extract the offset)
     * @return \vc\Data\Tag
     */
    static private function buildTag ( &$content, $tag1, $tag2 )
    {
        $offset = $tag1[1][1] + strlen($tag1[1][0]);

        // Use the position of the next tag to extract the text for this tag
        if ( !empty($tag2) )
            $text = substr($content, $offset, $tag2[0][1] - $offset);
        else
            $text = substr($content, $offset);

        return new \vc\Data\Tag( $tag1[1][0], trim($text) );
    }

    /**
     * Parses a comment string into an object
     *
     * @param String $content The text to parse
     * @return \vc\Data\Comment
     */
    public function parse ( $content )
    {
        $content = trim($content, " */\t\r\n");
        $content = str_replace( array("\r\n", "\n", "\r"), "\n", $content );

        // Clean up the leading white space and astericks on each line
        $content = preg_replace('/^[ \t\*]+/m', '', $content);

        // Match any tags and separate them from the text
        if (preg_match_all(
            '/^@(\w+)/m',
            $content,
            $tags,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE
        )) {
            $offset = reset($tags);
            $text = trim( substr($content, 0, $offset[0][1]) );
        }
        else {
            $text = $content;
        }

        $comment = new \vc\Data\Comment($text);

        // Build and add each tag to this comment
        foreach ( $tags AS $key => $tag ) {
            $comment->addTag(
                self::buildTag(
                    $content,
                    $tag,
                    isset($tags[$key + 1]) ? $tags[$key + 1] : NULL
                )
            );
        }

        return $comment;
    }

}

