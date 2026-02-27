<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait UploadFile
{
    public static function upload(
        ?UploadedFile $file,
        string $path,
        ?string $oldFile = null
    ): ?string {

        if (!$file) {
            return null;
        }

        $disk = Storage::disk('public');

        // Normalize path
        $path = rtrim($path, '/') . '/';

        // Delete old file (oldFile now contains full path)
        if ($oldFile && $disk->exists($oldFile)) {
            $disk->delete($oldFile);
        }

        // Generate filename
        $filename = bin2hex(random_bytes(16)) . '.' . $file->extension();

        $fullPath = $path . $filename;

        $disk->putFileAs($path, $file, $filename);

        return $fullPath;   // STORE FULL PATH
    }

    public static function delete(?string $file): bool
    {
        if (!$file) {
            return false;
        }

        return Storage::disk('public')->delete($file);
    }
}
