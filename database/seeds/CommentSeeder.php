<?php

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = [
            ['book_id'=>'1', 'content'=>'Sách không hay lắm','created_by'=>'MienNTH', 'updated_by'=>'MienNTH'],
            ['book_id'=>'1', 'content'=>'Sách không hay lắm','created_by'=>'MienNTH', 'updated_by'=>'MienNTH'],
            ['book_id'=>'2', 'content'=>'Sách không hay lắm','created_by'=>'MienNTH', 'updated_by'=>'MienNTH'],
        ];
        foreach ($comments as $comment) { 
            Comment::create(
                array( 'book_id' => $comment['book_id'], 'content' => $comment['content'], 
            'created_by'=>$comment['created_by'],'updated_by'=>$comment['updated_by'],
            )
        ); 
        } 
    }
}
