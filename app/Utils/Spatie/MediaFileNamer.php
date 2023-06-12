<?php

namespace App\Utils\Spatie;

use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Support\FileNamer\FileNamer;

class MediaFileNamer extends FileNamer
{
    public function conversionFileName(string $fileName, Conversion $conversion): string
    {
        return "{$this->getFileName($fileName)}__{$conversion->getName()}";
    }

    public function responsiveFileName(string $fileName): string
    {
        return $this->getFileName($fileName);
    }

    protected function getFileName(string $fileName): string
    {
        return pathinfo($fileName, PATHINFO_FILENAME);
    }
}
