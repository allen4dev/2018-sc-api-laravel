<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Favoritable;
use App\Traits\Shareable;

class Track extends Model
{
    use Favoritable;
    use Shareable;

    protected static function boot()
    {
        static::deleting(function ($model) {
            $model->replies()->delete();

        });

        parent::boot();
    }

    protected $fillable = [ 'title', 'published', 'photo', 'src' ];

    protected $casts = [
        'user_id'   => 'int',
        'published' => 'boolean',
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
        return $this->morphMany(Reply::class, 'replyable');
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comment($details)
    {
        return $this->replies()->create([
            'user_id' => auth()->id(),
            'body'   => $details['body'],
        ]);
    }

    public function getFavoritedCountAttribute() {
        return $this->favorites()->count();
    }

    public function getSharedCountAttribute() {
        return $this->shared()->count();
    }
    
    public function getRepliesCountAttribute() {
        return $this->replies()->count();
    }
    

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopePublishedById($query, $ids)
    {
        return $query
                ->whereIn('id', $ids)
                ->where([
                    'published' => true,
                    'user_id'   => auth()->id(),
                    'album_id'  => null,
                ]);
    }
}
