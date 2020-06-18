<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;
    protected $fillable=['url'];
    public function bookImages() {
        return $this->hasMany('App\Models\BookImage');
    }
}
