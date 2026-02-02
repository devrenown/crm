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
    ): ?string {

        if (!$file) {
            return null;
        }

        $disk = Storage::disk('private');

        // Delete old file if exists
        if ($oldPath && $disk->exists($oldPath)) {
            $disk->delete($oldPath);
        }

        // Ensure folder exists
        if (!$disk->exists($path)) {
           $disk->makeDirectory($path); 
        }
        
        $filename = uniqid('', true) . '.enc';
        $fullPath = $path . $filename;

        // Encrypt file
        $data       = file_get_contents($file->getRealPath());
        $encrypted  = FileEncryptionService::encrypt($data);

        // Store in private disk
        $disk->put($fullPath, $encrypted);

        return $fullPath; // store in DB
    }


    /**
     * Upload and encrypt a file to the private disk
     */
    // public function uploadEncrypted(
    //     UploadedFile $file,
    //     string $directory,      // e.g., "employee_ids" or "education"
    //     string $tenantDomain,   // e.g., "acme"
    //     int $userId,
    //     ?int $objectId = null,  // optional, like leave_id
    //     ?string $oldPath = null
    // ): string {
    //     // Build the full path
    //     $path = $this->makeFilePath($directory, $tenantDomain, $userId, $objectId);

    //     // Ensure folder exists
    //     Storage::disk('private')->makeDirectory($path);

    //     // Delete old file if exists
    //     if ($oldPath) {
    //         Storage::disk('private')->delete($oldPath);
    //     }

        
    //     $filename = uniqid('', true) . '.enc';
    //     //$filename = uniqid('', true) . '.' . $file->extension() . '.enc';
    //     $fullPath = trim($path, '/') . '/' . $filename;

    //     // Encrypt file
    //     $data = file_get_contents($file->getRealPath());
    //     $encrypted = FileEncryptionService::encrypt($data);

    //     // Store in private disk
    //     Storage::disk('private')->put($fullPath, $encrypted);

    //     return $fullPath; // store in DB
    // }

    /**
     * Delete encrypted file from private storage
     */
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

    /**
     * Delete encrypted file from private storage
     */
    // public function deleteEncrypted(?string $path): void
    // {
    //     if ($path) {
    //         Storage::disk('private')->delete($path);
    //     }
    // }

    /**
     * Helper to generate consistent paths for files
     */
    public function makeFilePath(string $type, string $tenantDomain, int $userId, ?int $objectId = null): string
    {
        $path = "$tenantDomain/$userId/$type";
        if ($objectId) {
            $path .= "/$objectId";
        }
        return $path;
    }
}
