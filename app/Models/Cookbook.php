<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\ActionButtonTrait;

class Cookbook extends Model
{
    public $timestamps = false;
    use ActionButtonTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'cookbook';
    protected $fillable = [
        'name','thumb', 'description', 'timer', 'people_num', 'difficulty', 'staple_food', 'publisher'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
