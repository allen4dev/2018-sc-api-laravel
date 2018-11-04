<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [ 'title', 'published' ];
    protected $casts = [ 'user_id' => 'int' ];

    public function path()
    {
        return '/api/albums/' . $this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
