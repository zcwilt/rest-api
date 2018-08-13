<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\Fixtures\App\Models\ZcwiltDummy1;
use Tests\Fixtures\App\ZcwiltDummy;
use Tests\Fixtures\Models\ZcwiltPost;
use Zcwilt\Api\ApiQueryParser;
use Zcwilt\Api\ParserFactory;
use Illuminate\Support\Facades\Request;
use Tests\Fixtures\Models\ZcwiltUser;
use Faker\Faker;

abstract class TestCase extends Orchestra
{
    public function createTables()
    {
        Schema::dropIfExists('zcwilt_dummy');
        Schema::create('zcwilt_dummy', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 100)->unique();
            $table->integer('age');
            $table->timestamps();
        });
        Schema::dropIfExists('zcwilt_dummy1');
        Schema::create('zcwilt_dummy1', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 100)->unique();
            $table->integer('age');
            $table->timestamps();
        });

        Schema::dropIfExists('zcwilt_users');
        Schema::create('zcwilt_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 100)->unique();
            $table->integer('age');
            $table->timestamps();
        });
        Schema::dropIfExists('zcwilt_posts');
        Schema::create('zcwilt_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('comment');
            $table->timestamps();
        });
    }

    public function seedTables()
    {
        $user1 = ZcwiltUser::create([
            'name' => 'name1',
            'email' => 'test1@gmail.com',
            'age' => 15,
        ]);
        $user2 = ZcwiltUser::create([
            'name' => 'name2',
            'email' => 'test2@gmail.com',
            'age' => 30,
        ]);
        $user3 = ZcwiltUser::create([
            'name' => 'othername',
            'email' => 'foo@gmail.com',
            'age' => 5,
        ]);

        ZcwiltPost::create([
            'user_id' => $user1->id,
            'comment' => '1foo'
        ]);
        ZcwiltPost::create([
            'user_id' => $user1->id,
            'comment' => '1bar'
        ]);
        ZcwiltPost::create([
            'user_id' => $user1->id,
            'comment' => '1bin'
        ]);

        ZcwiltDummy::create([
            'name' => 'name1',
            'email' => 'test1@gmail.com',
            'age' => 15,
        ]);
        ZcwiltDummy1::create([
            'name' => 'name1',
            'email' => 'test1@gmail.com',
            'age' => 15,
        ]);
    }

    public function getRequestResults()
    {
        $api = new ApiQueryParser(new ParserFactory());
        $api->parseRequest(Request::instance());
        $api->buildParsers();
        $query = $api->buildQuery(new ZcwiltUser);
        $result = $query->get()->toArray();
        return $result;
    }
}
