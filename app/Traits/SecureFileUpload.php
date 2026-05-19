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
    if ($data === false) {
        throw new \RuntimeException('Failed to read uploaded file');
    }

    // Detect real mime FIRST
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $realMime = $finfo->file($file->getRealPath());

    // Allow only PDF & images
    if (
        $realMime !== 'application/pdf' &&
        !str_starts_with($realMime, 'image/')
    ) {
        throw new \Exception('Only PDF and image files are allowed');
    }

    // Extra protection: validate PDF header
    if ($realMime === 'application/pdf') {
        if (substr($data, 0, 4) !== '%PDF') {
            throw new \Exception('Invalid PDF file (fake or corrupted)');
        }
    }

    // Now encrypt
    $encrypted = FileEncryptionService::encrypt($data);

    // Store AFTER validation
    $disk->put($fullPath, $encrypted);

    return [
        'path' => $fullPath,
        'mime' => $realMime,
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
