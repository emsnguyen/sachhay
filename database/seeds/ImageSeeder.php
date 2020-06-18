<?php

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            ['id'=>1, 'url'=>'https://cdn0.fahasa.com/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/8/9/8935270700058-1_1_1.jpg'],
            ['id'=>2, 'url'=>'https://sachvui.com/cover/2018/homo-deus-luoc-su-tuong-lai.jpg'],
            ['id'=>3, 'url'=>'https://cf.shopee.vn/file/a342f8bf13b77cda8bd54cb45c2a24e5'],
        ];
        foreach ($images as $image) { 
            Image::create(
                array( 'id' => $image['id'], 'url' => $image['url'])
            ); 
        } 
    }
}
