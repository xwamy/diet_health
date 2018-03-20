<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cityid', 'city', 'provinceid'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function parentProvinces()
    {
        return $this->belongsTo('App\Model\Provinces', 'provinceid', 'provinceid');
    }

    public function childrenArea()
    {
        return $this->hasMany('App\Model\Area', 'cityid', 'cityid');
    }
}
