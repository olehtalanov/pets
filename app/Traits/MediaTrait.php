<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait MediaTrait
{
    protected string $collectionName = 'gallery';

    public function media(Model $model): MediaCollection
    {
        return $model->getMedia($this->getCollectionName());
    }

    public function upload(Model $model, array|UploadedFile $files): MediaCollection
    {
        foreach ($files as $file) {
            $model
                ->addMedia($file)
                ->toMediaCollection($this->getCollectionName());
        }

        return $this->media($model);
    }

    public function destroyMedia(Media $media): void
    {
        $media->delete();
    }

    public function getCollectionName(): string
    {
        return $this->collectionName;
    }
}
