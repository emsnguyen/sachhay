<?php

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = [
            ['title'=>'Title 1', 'isbn'=>'DFSFSD234','author'=>'Author 1', 'publisher'=>'Publisher A', 'review'=>'Sách rất hay', 'created_by'=>'MienNTH', 'updated_by'=>'MienNTh'],
            ['title'=>'Title 2', 'isbn'=>'DFSFSD234','author'=>'Author 2', 'publisher'=>'Publisher A', 'review'=>'Sách rất hay', 'created_by'=>'MienNTH', 'updated_by'=>'MienNTh'],
            ['title'=>'Title 3', 'isbn'=>'DFSFSD234','author'=>'Author 3', 'publisher'=>'Publisher A', 'review'=>'Sách rất hay', 'created_by'=>'MienNTH', 'updated_by'=>'MienNTh'],
            ['title'=>'Title 4', 'isbn'=>'DFSFSD234','author'=>'Author 4', 'publisher'=>'Publisher A', 'review'=>'Sách rất hay', 'created_by'=>'MienNTH', 'updated_by'=>'MienNTh'],
            ['title'=>'Title 5', 'isbn'=>'DFSFSD234','author'=>'Author 5', 'publisher'=>'Publisher A', 'review'=>'Sách rất hay', 'created_by'=>'MienNTH', 'updated_by'=>'MienNTh'],
        ];
        foreach ($books as $book) { 
            Book::create(array( 'title' => $book['title'], 'isbn' => $book['isbn'], 'author' => $book['author'], 'publisher' => $book['publisher'],
                'review'=>$book['review'],'created_by'=>$book['created_by'],'updated_by'=>$book['updated_by'],
                )); 
        } 
    }
}
