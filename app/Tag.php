<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [ 'name' ];

    public function path()
    {
        return '/api/tags/' . $this->id;
    }

    public function tracks()
    {
        return $this->morphedByMany(Track::class, 'taggable');
    }

    public function albums()
    {
        return $this->morphedByMany(Album::class, 'taggable');
    }

    public function playlists()
    {
        return $this->morphedByMany(Playlist::class, 'taggable');
    }

    public function getAlbumsCountAttribute()
    {
        return $this->albums()->count();
    }

    public function getPlaylistsCountAttribute()
    {
        return $this->playlists()->count();
    }
    
    public function getTracksCountAttribute()
    {
        return $this->tracks()->count();
    }

}
