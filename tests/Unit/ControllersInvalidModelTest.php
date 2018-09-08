<?php

namespace Tests\Unit;

use Tests\Fixtures\Controllers\Api\ZcwiltDummy2Controller;
use Zcwilt\Api\ModelMakerFactory;
use Tests\TestCase;

class ControllersInvalidModelTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTables();
        $this->seedTables();
    }

    public function testControllerInvalidModel()
    {
        $this->expectException(\Exception::class);
        new ZcwiltDummy2Controller(new ModelMakerFactory());
    }
}
