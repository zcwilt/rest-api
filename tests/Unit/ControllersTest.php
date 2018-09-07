<?php

namespace Tests\Unit;

use Tests\Fixtures\Controllers\Api\ZcwiltDummyController;
use Tests\Fixtures\Controllers\Api\ZcwiltDummy1Controller;
use Tests\Fixtures\Controllers\Api\ZcwiltDummy2Controller;
use Tests\Fixtures\Controllers\Api\ZcwiltUserController;
use Zcwilt\Api\ModelMakerFactory;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;
use Tests\Fixtures\Models\ZcwiltUser;

class ControllersTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTables();
        $this->seedTables();
    }

    public function testControllerIndexBadParser()
    {
        $request = Request::create('/index', 'GET', [
            'title' => 'foo',
            'text' => 'bar',
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $this->assertTrue($response->getStatusCode() === 400);
        $this->assertTrue(json_decode($response->getContent())->error->message === "Can't find parser class");
    }

    public function testControllerIndexNoParser()
    {
        $request = Request::create('/index', 'GET', [
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 15); //default pagination = 15
    }
    
    public function testControllerIndexWithWhereParser()
    {
        $request = Request::create('/index', 'GET', [
            'where' => 'id:eq:2'
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 1);
        $this->assertTrue($response->data[0]->id === 2);
    }
    public function testControllerIndexWithWhereInParser()
    {
        $request = Request::create('/index', 'GET', [
            'whereIn' => 'id:(1,2)'
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 2);
        $this->assertTrue($response->data[0]->id === 1);
    }

    public function testControllerShow()
    {
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->show(1);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data->id === 1);
        $response = $controller->show(1001);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->error->message === 'item does not exist');
    }

    public function testControllerStoreFails()
    {
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $request = Request::create('/index', 'POST', [
            'email' => 'dirk@holisticdetective.com',
            'name' => 'Dirk Gently'
        ]);
        $response = $controller->store($request);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->error->status_code === 400);
        $message = $response->error->message;
        $this->assertContains('SQLSTATE', $message);
        $request = Request::create('/index', 'POST', [
        ]);
        $response = $controller->store($request);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->error->status_code === 400);
        $message = $response->error->message->email[0];
        $this->assertContains('The email field is required.', $message);
    }

    public function testControllerStorePasses()
    {
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $request = Request::create('/index', 'POST', [
            'email' => 'dirk@holisticdetective.com',
            'name' => 'Dirk Gently',
            'age' => 38
        ]);
        $response = $controller->store($request);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data->age === 38);
    }

    public function testControllerUpdate()
    {
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $request = Request::create('/index', 'PUT', [
        ]);
        $response = $controller->update(1001, $request);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->error->message === 'item does not exist');
        $response = $controller->update(1, $request);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->error->status_code === 400);
        $message = $response->error->message->email[0];
        $this->assertContains('The email field is required.', $message);
        $request = Request::create('/index', 'POST', [
            'email' => 'name1@gmail.com',
            'name' => 'Dirk Gently'
        ]);
        $response = $controller->update(1, $request);
        $response = json_decode($response->getContent());
        $message = $response->error->message->email[0];
        $this->assertContains('The email has already been taken.', $message);
        $request = Request::create('/index', 'POST', [
            'email' => 'dirk@holisticdetective.com',
            'name' => 'Dirk Gently'
        ]);

        $response = $controller->update(1, $request);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data->id === 1);
        $this->assertTrue($response->data->name === 'Dirk Gently');
    }

    public function testControllerAppNamespace()
    {
        $request = Request::create('/index', 'GET', [
            'where' => 'id:eq:2'
        ]);
        $controller = new ZcwiltDummyController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 0);
    }

    public function testControllerAppModelNamespace()
    {
        $request = Request::create('/index', 'GET', [
            'where' => 'id:eq:2'
        ]);
        $controller = new ZcwiltDummy1Controller(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 0);
    }
    public function testControllerInvalidModel()
    {
        $this->expectException(\Exception::class);
        new ZcwiltDummy2Controller(new ModelMakerFactory());
    }

    public function testUpdateByQuery()
    {
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $request = Request::create('/UpdateByQuery', 'PUT', [
            'where' => 'id:eq:2', 'fields' => ['name' => 'foobar']
        ]);
        $response = $controller->updateByQuery($request);
        $response = json_decode($response->getContent());
        $this->assertTrue($response->data === 'affected rows = 1');
        $request = Request::create('/index', 'GET', [
            'where' => 'id:eq:2'
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 1);
        $this->assertTrue($response->data[0]->name === 'foobar');

        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $request = Request::create('/UpdateByQuery', 'PUT', [
            'where' => 'id:eq:2', 'fields' => ['nam' => 'foobar']
        ]);
        $response = $controller->updateByQuery($request);
        $response = json_decode($response->getContent());
        $this->assertContains('SQLSTATE', $response->error->message);
    }
}
