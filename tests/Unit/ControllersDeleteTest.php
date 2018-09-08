<?php

namespace Tests\Unit;

use Tests\Fixtures\Controllers\Api\ZcwiltUserController;
use Zcwilt\Api\ModelMakerFactory;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;
use Tests\Fixtures\Models\ZcwiltUser;

class ControllersDeleteTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTables();
        $this->seedTables();
    }

    public function testControllerSimpleDelete()
    {
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $model = new ZcwiltUser();
        $testResult = $model->all();
        $controller->destroy(1);
        $result = $model->all()->toArray();
        $this->assertTrue(count($result) === count($testResult)-1);
        $response = $controller->destroy(1001);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->error->message === 'item does not exist');
    }

    public function testControllerDeleteByQuery()
    {
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $request = Request::create('/deleteByQuery', 'DELETE', [
            'where' => 'id:eq:2'
        ]);
        $response = $controller->destroyByQuery($request);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data[0]->id === 2);
        $request = Request::create('/deleteByQuery', 'DELETE', [
            'where' => 'id:eq:1001'
        ]);
        $response = $controller->destroyByQuery($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 0);
        $request = Request::create('/deleteByQuery', 'DELETE', [
            'where' => 'foo:eq'
        ]);
        $response = $controller->destroyByQuery($request);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->error->message === 'where parser - invalid parameters');
    }

    public function testControllerDeleteShowWithTrashed()
    {
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $model = new ZcwiltUser();
        $testResult = $model->all();
        $controller->destroy(1);

        $request = Request::create('/index', 'GET', [
            'withTrashed' => '', 'paginate' => 'no'
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());

        $this->assertTrue(count($testResult) === count($response->data));
    }

    public function testControllerDeleteShowOnlyTrashed()
    {
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $model = new ZcwiltUser();
        $controller->destroy(1);

        $request = Request::create('/index', 'GET', [
            'onlyTrashed' => '', 'paginate' => 'no'
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());

        $this->assertTrue(count($response->data) === 1);
    }
}
