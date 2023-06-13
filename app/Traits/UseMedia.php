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
        return $this->parentAddMedia($file)->usingFileName($file->hashName());
    }
}
