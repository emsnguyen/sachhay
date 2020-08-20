<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;
    protected $fillable=['url', 'book_id'];
    /**
     * @var mixed
     */
    private $url;
    /**
     * @var mixed
     */
    private $book_id;

    public function book() {
        return $this->belongsTo('App\Models\Book');
    }
}
