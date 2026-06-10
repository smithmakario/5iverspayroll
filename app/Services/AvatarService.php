<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AvatarService
{
    private const MAX_DIMENSION = 320;

    public function store(UploadedFile $file, int $userId): string
    {
        $directory = "avatars/{$userId}";
        Storage::disk('public')->makeDirectory($directory);

        $path = "{$directory}/".Str::uuid().'.jpg';
        $fullPath = Storage::disk('public')->path($path);

        $this->resizeAndSave($file->getRealPath(), $fullPath, $file->getMimeType() ?? '');

        return $path;
    }

    private function resizeAndSave(string $source, string $destination, string $mime): void
    {
        $image = match ($mime) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($source),
            'image/png' => imagecreatefrompng($source),
            'image/webp' => imagecreatefromwebp($source),
            default => throw new InvalidArgumentException('Unsupported image type.'),
        };

        if ($image === false) {
            throw new InvalidArgumentException('Could not process the uploaded image.');
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $max = self::MAX_DIMENSION;

        if ($width > $max || $height > $max) {
            $ratio = min($max / $width, $max / $height);
            $newWidth = max(1, (int) round($width * $ratio));
            $newHeight = max(1, (int) round($height * $ratio));
            $resized = imagecreatetruecolor($newWidth, $newHeight);

            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $fill = imagecolorallocatealpha($resized, 255, 255, 255, 0);
            imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $fill);

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        imagejpeg($image, $destination, 85);
        imagedestroy($image);
    }
}
