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

    public function replyable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->morphMany(Reply::class, 'replyable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment($details)
    {
        return $this->replies()->create([
            'user_id' => auth()->id(),
            'body' => $details['body'],
        ]);
    }
}
