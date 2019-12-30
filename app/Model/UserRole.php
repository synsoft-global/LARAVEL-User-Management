<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
   
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'capabilities'
    ];

    public function users()
    {       
        return $this->hasMany('App\Model\User','id');
    }
}
