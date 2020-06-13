<?php

use App\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            ['image' => 'site/img/settings/sliders/slide1.png'],
            ['image' => 'site/img/settings/sliders/slide2.png'],
            ['image' => 'site/img/settings/sliders/slide3.png'],
            ['image' => 'site/img/settings/sliders/slide4.png'],
            ['image' => 'site/img/settings/sliders/slide5.png']
        ];
        foreach ($images as $image)
            Slider::create($image);
    }
}
