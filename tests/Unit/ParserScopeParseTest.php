<?php

namespace Tests\Unit;

use Zcwilt\Api\Exceptions\ParserParameterCountException;
use Zcwilt\Api\Exceptions\ParserInvalidParameterException;
use Zcwilt\Api\ParserFactory;
use Tests\DatabaseTestCase;
use Illuminate\Support\Facades\Request;
use Tests\Fixtures\Models\ZcwiltUser;

class ParserScopeParseTest extends DatabaseTestCase
{
    public function testScopeParserParseTestNoParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('scope');
        $this->expectException(ParserParameterCountException::class);
        $parser->parse('');
    }

    public function testScopeParserParseTestInvalidScope()
    {
        Request::instance()->query->set('scope', 'invalid');
        $this->expectException(ParserInvalidParameterException::class);
        $this->getRequestResults();
    }

    public function testScopeParserParseTestValidScope()
    {
        Request::instance()->query->set('scope', 'teenager');
        $result = $this->getRequestResults();
        $count = collect($result)->whereBetween('age', [13,19])->count();
        $this->assertEquals($count, 7);
    }

    public function testScopeParserParseWithOrderBy()
    {
        $request = Request::create('/index', 'GET', [
            'scope' => 'teenager',
            'sort' => '-age',
        ]);
        $result  = $this->getRequestResults($request);
        $count = collect($result)->whereBetween('age', [13,19])->count();
        $this->assertEquals($count, 7);
        $firstAge= $result[0]['age'];
        $this->assertEquals($firstAge, 19);
    }

}
