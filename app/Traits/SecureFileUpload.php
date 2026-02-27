<?php

namespace App\Traits;

use App\Services\FileEncryptionService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait SecureFileUpload
{

    /**
     * Upload and encrypt a file to the private disk
     */
    public static function uploadEncrypted(
    ?UploadedFile $file,
    string $path,
    ?string $oldPath = null
): ?array {

    if (!$file) {
        return null;
    }

    $disk = Storage::disk('private');

    // Delete old file
    if ($oldPath && $disk->exists($oldPath)) {
        $disk->delete($oldPath);
    }

    // Ensure folder exists
    if (!$disk->exists($path)) {
        $disk->makeDirectory($path);
    }

    // Secure random filename
    $filename = bin2hex(random_bytes(16)) . '.enc';
    $fullPath = rtrim($path, '/') . '/' . $filename;

    // Encrypt file
    $data = file_get_contents($file->getRealPath());
    $encrypted = FileEncryptionService::encrypt($data);

    // Store encrypted file
    $disk->put($fullPath, $encrypted);

    return [
        'path' => $fullPath,
        'mime' => $file->getMimeType(),
        //'original_name' => $file->getClientOriginalName(),
        //'size' => $file->getSize(),
    ];
}

    public static function deleteEncrypted(?string $path): void
    {
        if (!$path) {
            return;
        }

        $disk = Storage::disk('private');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }
}
