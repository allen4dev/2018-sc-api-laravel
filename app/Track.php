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

    public function reply($details)
    {
        return $this->replies()->create($details);
    }
}
