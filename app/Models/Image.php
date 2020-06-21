<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;
    protected $fillable=['url'];
    public function book() {
        return $this->belongsTo('App\Models\Book');
    } 
}
