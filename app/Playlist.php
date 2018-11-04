<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Favoritable;

use App\Track;

class Playlist extends Model
{
    use Favoritable;

    protected $fillable = [ 'title' ];
    protected $casts = [ 'user_id' => 'int' ];

    public function path()
    {
        return '/api/playlists/' . $this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tracks()
    {
        return $this->belongsToMany(Track::class);
    }

    public function addTrack(Track $track)
    {
        $this->tracks()->attach($track->id, [ 'user_id' => auth()->id() ]);

        return $this;
    }
}
