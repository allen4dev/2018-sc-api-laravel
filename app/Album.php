<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Favoritable;
use App\Traits\Shareable;

class Album extends Model
{
    use Favoritable;
    use Shareable;

    protected $fillable = [ 'title', 'published', 'user_id', 'photo' ];
    protected $casts = [ 'user_id' => 'int' ];

    protected static function boot()
    {
        static::deleting(function ($model) {
            $model->tracks()->update([ 'album_id' => null ]);
        });

        parent::boot();
    }

    public function path()
    {
        return '/api/albums/' . $this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function getFavoritedCountAttribute()
    {
        return $this->favorites()->count();
    }

    public function getSharedCountAttribute()
    {
        return $this->shared()->count();
    }

    public function getTracksCountAttribute()
    {
        return $this->tracks()->count();
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
