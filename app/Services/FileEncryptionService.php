<?php

namespace App\Services;

class FileEncryptionService
{
    private const CIPHER = 'aes-256-gcm';

    private static function key(): string
    {
        $key = config('files.encryption_key');

        if (!$key) {
            throw new \RuntimeException('FILE_ENCRYPTION_KEY not set in config/files.php');
        }

        if (str_starts_with($key, 'base64:')) {
            $key = substr($key, 7);
        }

        $key = base64_decode($key, true);

        if ($key === false || strlen($key) !== 32) {
            throw new \RuntimeException('FILE_ENCRYPTION_KEY must be 32 bytes');
        }

        return $key;
    }

    public static function encrypt(string $data): string
    {
        $iv = random_bytes(12);
        $tag = '';

        $ciphertext = openssl_encrypt(
            $data,
            self::CIPHER,
            self::key(),
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return $iv . $tag . $ciphertext;
    }

    public static function decrypt(string $data): string
    {
        $iv = substr($data, 0, 12);
        $tag = substr($data, 12, 16);
        $ciphertext = substr($data, 28);

        return openssl_decrypt(
            $ciphertext,
            self::CIPHER,
            self::key(),
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
    }
}
