<?php

namespace App\Models;

use App\Traits\Admin\ActionButtonTrait;

class Cookbook extends Model
{
    use ActionButtonTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'timer', 'people_num', 'difficulty', 'staple_food', 'publisher'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
