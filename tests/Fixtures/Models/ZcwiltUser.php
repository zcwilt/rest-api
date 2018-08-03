<?php
namespace Tests\Fixtures\Models;

use \Illuminate\Database\Eloquent\Model;

class ZcwiltUser extends Model
{
    protected $fillable = ['name', 'email', 'age'];

    public function rules($id = 0)
    {
        return [
            'email' => 'required|unique:zcwilt_users'.($id ? ",email,$id" : ''),
            'name' => 'required'
        ];
    }

}