<?php

namespace Tests\Unit;

use Zcwilt\Api\ApiQueryParser;
use Zcwilt\Api\Exceptions\InvalidParserException;
use Zcwilt\Api\ParserFactory;
use Tests\TestCase;
use Illuminate\Support\Facades\Request;

class ParserWhereBetweenParseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTables();
        $this->seedTables();
    }
    public function testWhereBetweenParserParseTestNoParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('whereBetween');
        $this->expectException(InvalidParserException::class);
        $parser->parse('');
    }
    public function testWhereBetweenParserParseTestWithParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('whereBetween');
        $parser->parse('id:4:20');
        $tokenized = $parser->getTokenized();
        $this->assertTrue($tokenized[0] === 'id');
        $this->assertTrue($tokenized[1] === '4');
        $this->assertTrue($tokenized[2] === '20');
    }
    public function testWhereBetweenParserParseTestInvalidParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('whereBetween');
        $this->expectException(InvalidParserException::class);
        $parser->parse('id');
    }

    public function testWhereBetweenParserWithDummyData()
    {
        Request::instance()->query->set('whereBetween', 'age:10:45');
        $result  = $this->getRequestResults();
        $this->assertTrue(count($result) === 2);
        $this->assertTrue((int)$result[0]['age'] === 15);
        $this->assertTrue((int)$result[1]['age'] === 30);
        Request::instance()->query->set('whereBetween', 'age:5:6');
        $result  = $this->getRequestResults();
        $this->assertTrue(count($result) === 1);
        $this->assertTrue((int)$result[0]['age'] === 5);
    }

    public function testWhereNotBetweenParserWithDummyData()
    {
        Request::instance()->query->set('whereNotBetween', 'age:10:45');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['id'] === 3);
        $this->assertTrue(count($result) === 1);
    }

    public function testOrWhereBetweenParserWithDummyData()
    {
        Request::instance()->query->set('orWhereBetween', 'age:10:45');
        $result  = $this->getRequestResults();
        $this->assertTrue(count($result) === 2);
        $this->assertTrue((int)$result[0]['age'] === 15);
        $this->assertTrue((int)$result[1]['age'] === 30);
    }
    public function testOrWhereNotBetweenParserWithDummyData()
    {
        Request::instance()->query->set('orWhereNotBetween', 'age:10:45');
        $result  = $this->getRequestResults();
        $this->assertTrue(count($result) === 1);
        $this->assertTrue((int)$result[0]['age'] === 5);
    }
}
