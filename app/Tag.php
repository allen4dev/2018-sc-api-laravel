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
}
