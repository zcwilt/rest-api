<?php

namespace Tests\Unit;

use Zcwilt\Api\ApiQueryParser;
use Zcwilt\Api\Exceptions\InvalidParserException;
use Zcwilt\Api\ParserFactory;
use Tests\TestCase;
use Illuminate\Support\Facades\Request;

class ParserWhereParseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTables();
        $this->seedTables();
    }
    public function testWhereParserParseTestNoParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('where');
        $this->expectException(InvalidParserException::class);
        $parser->parse('');
    }
    public function testWhereParserParseTestWithParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('where');
        $parser->parse('id:eq:1');
        $tokenized = $parser->getTokenized();
        $this->assertTrue($tokenized[0] === 'id');
        $this->assertTrue($tokenized[1] === 'eq');
        $this->assertTrue($tokenized[2] === '1');
    }
    public function testWhereParserParseTestInvalidParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('where');
        $this->expectException(InvalidParserException::class);
        $parser->parse('id:eq');
    }
    public function testWhereParserParseTestInvalidOperator()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('where');
        $this->expectException(InvalidParserException::class);
        $parser->parse('id:foo:1');
    }

    public function testWhereParserWithDummyData()
    {
        Request::instance()->query->set('where', 'id:eq:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 30);
        $this->assertTrue(count($result) === 1);
        Request::instance()->query->set('where', 'id:noteq:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 15);
        $this->assertTrue((int)$result[1]['age'] === 5);
        $this->assertTrue(count($result) === 2);
        Request::instance()->query->set('where', 'id:lte:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 15);
        $this->assertTrue((int)$result[1]['age'] === 30);
        $this->assertTrue(count($result) === 2);
        Request::instance()->query->set('where', 'id:gte:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 30);
        $this->assertTrue((int)$result[1]['age'] === 5);
        $this->assertTrue(count($result) === 2);
        Request::instance()->query->set('where', 'id:gt:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 5);
        $this->assertTrue(count($result) === 1);
        Request::instance()->query->set('where', 'id:lt:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 15);
        $this->assertTrue(count($result) === 1);
    }
}