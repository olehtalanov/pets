<?php

namespace App\Repositories;

use App\Data\User\ProfileData;
use App\Models\User;
use Auth;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileRepository extends BaseRepository
{
    public function update(ProfileData $data): User
    {
        return tap(Auth::user())->update($data->toArray());
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function avatar(UploadedFile $file): Media
    {
        return Auth::user()
            ?->addMedia($file)
            ->toMediaCollection('avatar');
    }
}
