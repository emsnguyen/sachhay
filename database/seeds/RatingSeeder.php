<?php

use App\Models\Rating;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ratings = [
            ['book_id'=>1, 'value'=>1,'created_by'=>'MienNTH', 'updated_by'=>'MienNTH'],
            ['book_id'=>1, 'value'=>5,'created_by'=>'MienNTH', 'updated_by'=>'MienNTH'],
            ['book_id'=>2, 'value'=>2,'created_by'=>'MienNTH', 'updated_by'=>'MienNTH'],
        ];
        foreach ($ratings as $rating) { 
            Rating::create(
                array( 'book_id' => $rating['book_id'], 'value' => $rating['value'], 
            'created_by'=>$rating['created_by'],'updated_by'=>$rating['updated_by'],
            )
        ); 
        } 
    }
}
