<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Favoritable;

class Playlist extends Model
{
    use Favoritable;

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
