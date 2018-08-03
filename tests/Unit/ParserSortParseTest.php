<?php

namespace Tests\Unit;

use Zcwilt\Api\Exceptions\InvalidParserException;
use Zcwilt\Api\ParserFactory;
use Tests\TestCase;
use Illuminate\Support\Facades\Request;

class ParserSortParseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTables();
        $this->seedTables();
    }

    public function testSortParserParseTestNoParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('sort');
        $this->expectException(InvalidParserException::class);
        $parser->parse('');
    }
    public function testSortParserParseTestWithParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('sort');
        $parser->parse('z,-y');
        $tokenized = $parser->getTokenized();
        $this->assertTrue($tokenized[0]['field'] === 'z');
        $this->assertTrue($tokenized[0]['direction'] === 'ASC');
        $this->assertTrue($tokenized[1]['field'] === 'y');
        $this->assertTrue($tokenized[1]['direction'] === 'DESC');
    }
    public function testSortParserWithDummyData()
    {
        Request::instance()->query->set('sort', '-age');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 30);
        Request::instance()->query->set('sort', 'name');
        $result  = $this->getRequestResults();
        $this->assertTrue($result[0]['name'] === 'name1');
        $this->assertTrue((int)$result[2]['age'] === 5);
    }
}