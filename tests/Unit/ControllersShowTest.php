<?php

namespace Tests\Unit;

use Tests\Fixtures\Controllers\Api\ZcwiltUserController;
use Zcwilt\Api\ModelMakerFactory;
use Tests\DatabaseTestCase;

class ControllersShowTest extends DatabaseTestCase
{
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
}
