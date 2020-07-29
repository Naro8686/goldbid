<?php


namespace App\Settings;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

trait ImageTrait
{
    /**
     * @param $image
     * @return string
     */
    public function icon($image)
    {
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $iconPath = 'site/img/settings/icon/footer';
        $path = public_path($iconPath);
        $resize_image = Image::make($image->getRealPath());
        $resize_image->resize(256, 256, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . '/' . $image_name);
        return $iconPath . '/' . $image_name;
    }

    /**
     * @param $image
     * @param string $path
     * @param int $w
     * @param int $h
     * @return array
     */
    public function postUploadImage($image, $path = 'site/img/upload', int $w = 0, int $h = 0)
    {
        $success = true;
        $validator = Validator::make(['upload' => $image], [
            'upload' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
        ]);
        if ($validator->fails())
            return ['uploaded' => $success = false, 'error' => ['message' => $validator->errors()->getMessages()['upload'][0]]];
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $urlPath = asset($path);
        $path = public_path($path);
        if (!is_dir($path)) mkdir($path, 0755, true);
        if ($w > 0 || $h > 0) {
            $resize_image = Image::make($image->getRealPath());
            $resize_image->resize($w, $h, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . '/' . $image_name);
        } else {
            $image->move($path, $image_name);
        }
        return ['uploaded' => $success, 'fileName' => $image_name, 'url' => $urlPath . '/' . $image_name];
    }

    public function uploadImage($image, $path = 'site/img/upload', int $w = 0, int $h = 0)
    {
        $image_name = md5(rand(1, time())) . '.' . $image->getClientOriginalExtension();
        if (!is_dir(public_path($path))) mkdir(public_path($path), 0777, true);
        if ($w > 0 || $h > 0) {
            $resize_image = Image::make($image->getRealPath());
            $resize_image->resize($w, $h, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path($path) . '/' . $image_name);
        } else {
            $image->move(public_path($path), $image_name);
        }

        return "{$path}/{$image_name}";
    }

    public function imageCopy($image, $old_path = 'site/img/product', $new_path = 'site/img/product')
    {
        $img = null;
        if (is_file(public_path($image))) {
            $name = md5($image . microtime());
            $img = preg_replace("~^{$old_path}/(.*?)\.([a-z]{1,6})$~i", "{$new_path}/{$name}.$2", $image);
            Storage::disk('local_public')->copy($image, $img);
        }
        return $img;
    }
}
