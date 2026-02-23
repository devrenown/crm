<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait uploadFile
{
    public static function upload($file, $path, $oldFile = null)
    {
        if (!$file) {
            return null;
        }

        if ($oldFile && File::exists(public_path($path . $oldFile))) {
            File::delete(public_path($path . $oldFile));
        }

        if (!File::exists(public_path($path))) {
            File::makeDirectory(public_path($path), 0777, true);
        }

        $newFileName = time() . '_' . uniqid() . "." . $file->getClientOriginalName();

        $file->move(public_path($path), $newFileName);

        return $newFileName;
    }

    public static function delete($file, $path)
    {
        if (!$file) {
            return null;
        }

        if ($file && File::exists(public_path($path . $file))) {
            File::delete(public_path($path . $file));
            return true;
        }

        return false;
    }
}
