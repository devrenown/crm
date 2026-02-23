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

        // Delete old file
        if ($oldFile && $disk->exists($path . $oldFile)) {
            $disk->delete($path . $oldFile);
        }

        // Generate secure filename
        $filename = bin2hex(random_bytes(16)) . '.' . $file->extension();

        // Store file
        $disk->putFileAs($path, $file, $filename);

        return $filename;
    }

    public static function delete(?string $file, string $path): bool
    {
        if (!$file) {
            return false;
        }

        return Storage::disk('public')->delete($path . $file);
    }
}