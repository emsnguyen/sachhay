<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;
    protected $fillable=['title', 'isbn', 'author', 'publisher', 'review', 'created_by', 'updated_by'];
    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];
    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }
    public function ratings() {
        return $this->hasMany('App\Models\Rating');
    }
    public function images() {
        return $this->hasMany('App\Models\Image');
    }
}
