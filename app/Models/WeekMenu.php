<?php

namespace App\Models;

use App\Traits\Admin\ActionButtonTrait;

class WeekMenu extends Model
{
    use ActionButtonTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','cook_id','cookname','week','time','week_year'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
