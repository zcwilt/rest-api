<?php
namespace Tests\Fixtures\App\Models;

use \Illuminate\Database\Eloquent\Model;

class ZcwiltDummy1 extends Model
{
    protected $table = 'zcwilt_dummy1';
    protected $fillable = ['name', 'email', 'age'];
}
