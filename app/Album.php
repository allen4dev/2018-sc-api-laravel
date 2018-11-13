<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Favoritable;

class Album extends Model
{
    use Favoritable;

    protected static function boot()
    {
        static::deleting(function ($model) {
            $model->tracks()->update([ 'album_id' => null ]);
        });

        parent::boot();
    }

    protected $fillable = [ 'title', 'published', 'user_id' ];
    protected $casts = [ 'user_id' => 'int' ];

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

    public function getTracksCountAttribute()
    {
        return $this->tracks()->count();
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
