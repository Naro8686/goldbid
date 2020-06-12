<?php


namespace App\Settings;

use Intervention\Image\Facades\Image;

trait ImageTrait
{
    public function icon($image)
    {
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $iconPath = 'site/img/settings/icon/footer';
        $path = public_path($iconPath);
        $resize_image = Image::make($image->getRealPath());
        $resize_image->resize(256, 256, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . '/' . $image_name);

//        $destinationPath = public_path('/images/site/img/settings/icon/footer');
//        $image->move($destinationPath, $image_name);
        return $iconPath . '/' . $image_name;
    }
}
