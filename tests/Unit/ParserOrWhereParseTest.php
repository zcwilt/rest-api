<?php

namespace Tests\Unit;

use Zcwilt\Api\ApiQueryParser;
use Zcwilt\Api\Exceptions\InvalidParserException;
use Zcwilt\Api\ParserFactory;
use Tests\TestCase;
use Illuminate\Support\Facades\Request;

class ParserOrWhereParseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTables();
        $this->seedTables();
    }

    public function testOrWhereParserWithDummyData()
    {
        Request::instance()->query->set('orWhere', 'id:eq:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 30);
        $this->assertTrue(count($result) === 1);
        Request::instance()->query->set('orWhere', 'id:noteq:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 15);
        $this->assertTrue((int)$result[1]['age'] === 5);
        $this->assertTrue(count($result) === 2);
        Request::instance()->query->set('orWhere', 'id:lte:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 15);
        $this->assertTrue((int)$result[1]['age'] === 30);
        $this->assertTrue(count($result) === 2);
        Request::instance()->query->set('orWhere', 'id:gte:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 30);
        $this->assertTrue((int)$result[1]['age'] === 5);
        $this->assertTrue(count($result) === 2);
        Request::instance()->query->set('orWhere', 'id:gt:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 5);
        $this->assertTrue(count($result) === 1);
        Request::instance()->query->set('orWhere', 'id:lt:2');
        $result  = $this->getRequestResults();
        $this->assertTrue((int)$result[0]['age'] === 15);
        $this->assertTrue(count($result) === 1);
    }
}
