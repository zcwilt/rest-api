<?php
namespace Tests\Unit;

use Zcwilt\Api\ApiQueryParser;
use Zcwilt\Api\ParserFactory;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;
use Zcwilt\Api\Exceptions\InvalidParserException;

class BuildParsersTest extends TestCase
{
    public function testFailInvalidParser()
    {
        $api = new ApiQueryParser(new ParserFactory());
        Request::instance()->query->set('bar', 'foo');
        $api->parseRequest(Request::instance());
        $this->expectException(InvalidParserException::class);
        $api->buildParsers();
    }

    public function testPassParser()
    {
        $api = new ApiQueryParser(new ParserFactory());
        Request::instance()->query->set('sort', 'foo');
        $api->parseRequest(Request::instance());
        $api->buildParsers();
        $tokenized = $api->getQueryParts()[0]->getTokenized()[0];
        $this->assertTrue($tokenized['field'] === 'foo');
        $this->assertTrue($tokenized['direction'] === 'ASC');
    }
}
