<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [ 'name', 'user_id' ];

    public function path()
    {
        return "/api/tracks/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
