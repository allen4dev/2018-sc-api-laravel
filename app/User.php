<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function path()
    {
        return '/api/users/' . $this->id;
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function createTrack($details)
    {
        return $this->tracks()->create($details);
    }

    public function createPlaylist($details)
    {
        return $this->playlists()->create($details);
    }

    public function createAlbum($input)
    {
        $album = $this->albums()->create($input['details']);

        Track::whereIn('id', $input['tracks'])->update([ 'album_id' => $album->id ]);

        return $album;
    }
}
