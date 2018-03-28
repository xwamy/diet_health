<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Admin\ActionButtonTrait;

class Taste extends Model
{
    public $timestamps = false;
    use ActionButtonTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'taste';
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
