<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\ActionButtonTrait;

class CookingWay extends Model
{
    public $timestamps = false;

    use ActionButtonTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'cooking_way';
    protected $fillable = [
       'name','sort','cTime'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
