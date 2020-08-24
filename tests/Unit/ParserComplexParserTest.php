<?php

namespace Tests\Unit;

use Tests\DatabaseTestCase;
use Illuminate\Support\Facades\Request;
use Tests\Fixtures\Models\ZcwiltUser;

class ParserComplexParserTest extends DatabaseTestCase
{
    public function testComplexWithDummyData()
    {
        $testResult = ZcWiltUser::orWhere('id', '=', 1)->orWhere('id', '=', 2)->get()->toArray();
        $request = Request::create('/index', 'GET', [
            'orWhere' => ['id:eq:1', 'id:eq:2']
        ]);
        $result  = $this->getRequestResults($request);
        $this->assertTrue(count($result) === count($testResult));

        $testResult = ZcWiltUser::select('name', 'age')->orWhereIn('age', [19,30])->orWhereIn('age', [40,90])->get()->toArray();

        $request = Request::create('/index', 'GET', [
            'orWhereIn' => ['age:(19,30)', 'age:(40,90)'],
            'sort' => '-age',
            'columns' => 'name,age'
        ]);
        $result  = $this->getRequestResults($request);
        $this->assertTrue(count($result) === count($testResult));
        if (count($result) > 0) {
            $this->assertTrue(count($result[0]) === 2); // only returning 2 columns
        }
    }
}
