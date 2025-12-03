<?php

namespace App\Models;

use App\Media\SanitizeSvgAdder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
