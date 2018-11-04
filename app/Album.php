<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Favoritable;

class Album extends Model
{
    use Favoritable;

    protected $fillable = [ 'title', 'published', 'user_id' ];
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
