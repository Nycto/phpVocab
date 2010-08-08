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

require_once rtrim( __DIR__, "/" ) ."/../setup.php";

/**
 * Unit test
 */
class test_Parsing_Parse extends \vc\Test\TestCase
{

    /**
     * Returns a parser
     *
     * @return \vc\Parser\File\Comment
     */
    public function getParser ()
    {
        $brackets = new \vc\Parser\Brackets;
        $value = new \vc\Parser\Value( $brackets );
        $constant = new \vc\Parser\Constant($value);
        $path = new \vc\Parser\Path;
        $pathList = new \vc\Parser\PathList($path);
        $routine = new \vc\Parser\Routine\Body(
            new \vc\Parser\Routine\Args( $path, $value ),
            $brackets
        );
        $method = new \vc\Parser\Routine\Method( $routine );

        return new \vc\Parser\File\Comment(
            new \vc\Parser\Comment,
            new \vc\Parser\File\NSpaces(
                $path,
                new \vc\Parser\NSpace\Body(
                    new \vc\Parser\NSpace\Alias( $path ),
                    $constant,
                    new \vc\Parser\Routine\Func( $routine ),
                    new \vc\Parser\Object\Header(
                        $path,
                        $pathList,
                        new \vc\Parser\Object\Members(
                            $constant,
                            new \vc\Parser\Object\Signature(
                                new \vc\Parser\Object\Property( $value ),
                                $method
                            )
                        )
                    ),
                    new \vc\Parser\IFace\Header(
                        $pathList,
                        new \vc\Parser\IFace\Members(
                            $constant,
                            $method
                        )
                    )
                )
            )
        );
    }

    /**
     * Parses the given file
     *
     * @return \vc\Data\File
     */
    public function parseFile ( $path )
    {
        $reader = \vc\Tokens\Access::buildAccess(
            new \vc\Tokens\Parser(
                new \r8\Stream\In\URI( $path )
            )
        );

        $file = new \vc\Data\File( $path );
        $this->getParser()->parse( $file, $reader );
        return $file;
    }

    public function testParse ()
    {
        $file = $this->parseFile( vocab_TEST_DATA .'/Basic.php' );

        $this->assertThat( $file, $this->isInstanceOf( '\vc\Data\File' ) );

        $nspace = $file->getNamespaces();
        $this->assertArrayOfLength( 1, $nspace );
        $this->assertArrayHasKey(0, $nspace);

        $this->assertArrayOfLength(2, $nspace[0]->getAliases());
        $this->assertArrayOfLength(3, $nspace[0]->getConstants());

        $functions = $nspace[0]->getFunctions();
        $this->assertArrayOfLength(1, $functions);
        $this->assertArrayHasKey(0, $functions);
        $this->assertEquals( 'someFunction', $functions[0]->getName() );
        $this->assertArrayOfLength( 0, $functions[0]->getArgs() );

        $types = $nspace[0]->getTypes();
        $this->assertArrayOfLength(1, $types);
        $this->assertArrayHasKey(0, $types);

        $this->assertEquals('SampleClass', $types[0]->getName());
        $this->assertArrayOfLength(3, $types[0]->getConstants());
        $this->assertArrayOfLength(2, $types[0]->getProperties());

        $methods = $types[0]->getMethods();
        $this->assertArrayOfLength(3, $methods);

        $this->assertSame('getBoolean', $methods[0]->getName());
        $this->assertArrayOfLength(0, $methods[0]->getArgs());

        $this->assertSame('getData', $methods[1]->getName());
        $this->assertArrayOfLength(2, $methods[1]->getArgs());

        $this->assertSame('dump', $methods[2]->getName());
        $this->assertArrayOfLength(3, $methods[2]->getArgs());
    }

}

?>