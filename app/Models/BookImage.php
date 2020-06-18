<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookImage extends Model
{
    public $timestamps = false;
    protected $fillable=['book_id', 'image_id'];
    public function book() {
        return $this->belongsTo('App\Models\Book');
    }
    public function image() {
        return $this->belongsTo('App\Models\Image');
    }
}
