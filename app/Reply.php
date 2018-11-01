<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [ 'body', 'user_id' ];

    public function path()
    {
        return '/api/replies/' . $this->id;
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
