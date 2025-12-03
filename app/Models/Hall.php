<?php

namespace App\Models;

use App\Media\SanitizeSvgAdder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;

class Hall extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('floor_map')
            ->singleFile()
            ->acceptsMimeTypes(['image/svg+xml'])
            ->useDisk('public');
    }

    public function addMedia($file): FileAdder
    {
        return app(SanitizeSvgAdder::class)
            ->setSubject($this)
            ->setFile($file);
    }
}
