<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UseMedia
{
    use InteractsWithMedia {
        InteractsWithMedia::addMedia as parentAddMedia;
    }

    public function addMedia(string|UploadedFile $file): FileAdder
    {
        $parts = explode('.', $file->hashName());

        return $this->parentAddMedia($file)->usingFileName(
            substr($parts[0], 0, 12) . '.' . $parts[1]
        );
    }
}
