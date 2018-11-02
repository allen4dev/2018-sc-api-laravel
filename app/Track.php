<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [ 'title', 'published' ];

    protected $casts = [
        'user_id' => 'int',
    ];
    
    public function path()
    {
        return '/api/tracks/' . $this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function reply($details)
    {
        return $this->replies()->create([
            'user_id' => auth()->id(),
            'body'   => $details['body'],
        ]);
    }

    public function favorite()
    {
        $attributes = [
            'user_id' => auth()->id(),
            'type' => 'track',        
        ];

        if (! $this->isFavorited($attributes)) {
            $this->favorites()->create($attributes);
        }

        return $this;
    }

    protected function isFavorited($attributes)
    {
        return $this->favorites()->where($attributes)->exists();
    }
}
