<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SecureFileViewService
{
    public static function decryptFromPrivateDisk(string $path): string
    {
        $encrypted = Storage::disk('private')->get($path);
        return FileEncryptionService::decrypt($encrypted);
    }
}
