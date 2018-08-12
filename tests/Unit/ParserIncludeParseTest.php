<?php

namespace Tests\Unit;

use Zcwilt\Api\ApiQueryParser;
use Zcwilt\Api\Exceptions\InvalidParserException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Zcwilt\Api\ParserFactory;
use Tests\TestCase;
use Illuminate\Support\Facades\Request;
use Tests\Fixtures\Controllers\Api\ZcwiltUserController;
use Zcwilt\Api\ModelMakerFactory;

class ParserIncludeParseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTables();
        $this->seedTables();
    }
    public function testIncludesParserParseTestNoParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('includes');
        $this->expectException(InvalidParserException::class);
        $parser->parse('');
    }

    public function testIncludesParserParseTestWithParams()
    {
        $api = new ApiQueryParser(new ParserFactory());
        Request::instance()->query->set('includes', 'foo,bar');
        $api->parseRequest(Request::instance());
        $api->buildParsers();
        $tokenized = $api->getQueryParts()[0]->getTokenized()[0];
        $this->assertTrue($tokenized['field'] === 'foo');
        $tokenized = $api->getQueryParts()[0]->getTokenized()[1];
        $this->assertTrue($tokenized['field'] === 'bar');
    }

    public function testIncludesParserWithDummyData()
    {
        Request::instance()->query->set('includes', 'posts');
        $result  = $this->getRequestResults();
        $this->assertTrue(count($result) === 3);
        $post0 = $result[0]['posts'];
        $this->assertTrue($post0[0]['id'] === 1);
    }

    public function testIncludesParserWithDummyDataInvalidWith()
    {
        Request::instance()->query->set('includes', 'foos');
        $this->expectException(RelationNotFoundException::class);
        $this->getRequestResults();
    }

    public function testControllerIndexWithIncludesParserPass()
    {
        $request = Request::create('/index', 'GET', [
            'where' => 'id:eq:1', 'includes' => 'posts'
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 1);
        $this->assertTrue(count($response->data[0]->posts) === 3);
    }
    public function testControllerIndexWithIncludesParserFail()
    {
        $request = Request::create('/index', 'GET', [
            'where' => 'id:eq:1', 'includes' => 'foo'
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $message = $response->error->message;
        $this->assertContains('Call to undefined relationship', $message);
    }

}