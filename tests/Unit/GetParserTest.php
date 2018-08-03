<?php

namespace Tests\Unit;

use Zcwilt\Api\Exceptions\InvalidParserException;
use Zcwilt\Api\ParserFactory;
use Tests\TestCase;

class GetParserTest extends TestCase
{

    public function testFailParserFactory()
    {
        $parserFactory = new ParserFactory();
        $this->expectException(InvalidParserException::class);
        $parserFactory->getParser('action');
    }

    public function testPassParserFactory()
    {
        $parserFactory = new ParserFactory();
        $result = $parserFactory->getParser('sort');
        $this->assertTrue(count($result->getTokenized()) === 0);
    }

}