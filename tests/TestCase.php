<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\Fixtures\Models\ZcwiltPost;
use Zcwilt\Api\ApiQueryParser;
use Zcwilt\Api\ParserFactory;
use Illuminate\Support\Facades\Request;
use Tests\Fixtures\Models\ZcwiltUser;

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
        $userTableTestData = $this->createUserTableTestData();

        foreach ($userTableTestData as $user) {
            $userCreated = ZcwiltUser::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'age' => $user['age'],
            ]);
            foreach ($user['posts'] as $post) {
                ZcwiltPost::create([
                    'user_id' => $userCreated->id,
                    'comment' => $post['comment']
                ]);
            }
        }
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

    private function createUserTableTestData()
    {
        $data = [];
        $n = rand(16, 30);
        for ($i = 0; $i < $n; $i++) {
            $name = 'name' . $i;
            $email = $name . '@gmail.com';
            $age = rand(15, 76);
            $posts = $this->createUserPostsTestData($i);
            $data[] = ['name' => $name, 'email' => $email, 'age' => $age, 'posts' => $posts];
        }
        return $data;
    }

    private function createUserPostsTestData($userIndex)
    {
        $data = [];
        $n = rand(1, 3);
        for ($i = 0; $i < $n; $i++) {
            $comment = 'Comment ' . $i . ' for index ' . $userIndex;
            $data[] = ['comment' => $comment];
        }
        return $data;
    }
}
