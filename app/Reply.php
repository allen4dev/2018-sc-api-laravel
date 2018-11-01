<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [ 'body', ];

    public function path()
    {
        return '/api/replies/' . $this->id;
    }
}
