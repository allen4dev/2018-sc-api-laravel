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
}
