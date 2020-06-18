<?php

use App\Models\BookImage;
use Illuminate\Database\Seeder;

class BookbookImageseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bookImages = [
            ['book_id'=>1, 'image_id'=>1],
            ['book_id'=>2, 'image_id'=>2],
            ['book_id'=>3, 'image_id'=>3],
        ];
        foreach ($bookImages as $bookImage) { 
            BookImage::create(
                array( 'book_id' => $bookImage['book_id'], 'image_id' => $bookImage['image_id'])
            ); 
        } 
    }
}
