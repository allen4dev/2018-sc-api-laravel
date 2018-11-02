<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [ 'type', 'user_id', ];

    public function favoritable()
    {
        return $this->morphTo();
    }
}
