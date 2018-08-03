<?php

namespace Tests\Unit;

use Zcwilt\Api\ApiQueryParser;
use Zcwilt\Api\Exceptions\InvalidParserException;
use Zcwilt\Api\ParserFactory;
use Tests\TestCase;
use Illuminate\Support\Facades\Request;

class ParserWhereInParseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTables();
        $this->seedTables();
    }
    public function testWhereInParserParseTestNoParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('whereIn');
        $this->expectException(InvalidParserException::class);
        $parser->parse('');
    }
    public function testWhereInParserParseTestWithParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('whereIn');
        $parser->parse('id:(1,2)');
        $tokenized = $parser->getTokenized();
        $this->assertTrue($tokenized['col'] === 'id');
        $this->assertTrue(is_array($tokenized['in']));
        $this->assertTrue($tokenized['in'][0] === '1');
        $this->assertTrue($tokenized['in'][1] === '2');
    }
    public function testWhereInParserParseTestInvalidParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('whereIn');
        $this->expectException(InvalidParserException::class);
        $parser->parse('id');
    }

    public function testWhereInParserWithDummyData()
    {
        Request::instance()->query->set('whereIn', 'id:(1,2)');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 15);
        $this->assertTrue((int)$result[1]['age'] === 30);
        $this->assertTrue(count($result) === 2);
        Request::instance()->query->set('whereIn', 'id:(1,2)');
        Request::instance()->query->set('sort', '-id');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 30);
        $this->assertTrue((int)$result[1]['age'] === 15);
        $this->assertTrue(count($result) === 2);
    }

    public function testWhereNotInParserWithDummyData()
    {
        Request::instance()->query->set('whereNotIn', 'id:(1,2)');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['id'] === 3);
        $this->assertTrue(count($result) === 1);
    }

    public function testOrWhereInParserWithDummyData()
    {
        Request::instance()->query->set('orWhereIn', 'id:(1,2)');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 15);
        $this->assertTrue((int)$result[1]['age'] === 30);
        $this->assertTrue(count($result) === 2);
    }
    public function testOrWhereNotInParserWithDummyData()
    {
        Request::instance()->query->set('orWhereNotIn', 'id:(1,2)');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['id'] === 3);
        $this->assertTrue(count($result) === 1);
    }
}