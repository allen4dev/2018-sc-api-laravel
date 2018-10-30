<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = [ 'title' ];

    public function path()
    {
        return '/api/playlists/' . $this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
