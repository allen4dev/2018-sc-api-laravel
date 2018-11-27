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
        'username',
        'fullname',
        'email',
        'password',
        'avatar_url',
        'profile_image',
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

    public function getTracksCountAttribute()
    {
        return $this->tracks()->count();
    }

    public function getPlaylistsCountAttribute()
    {
        return $this->playlists()->count();
    }

    public function getAlbumsCountAttribute()
    {
        return $this->albums()->count();
    }

    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    public function getFollowingsCountAttribute()
    {
        return $this->followings()->count();
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
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function createTrack($details)
    {
        $photoPath = $details['photo']->store('tracks/photos', 'public');
        $audioPath = $details['src']->store('tracks/audio', 'public');

        $tags = collect(explode(',', $details['tags']));

        $tags = Tag::whereIn('id', $tags)->get();

        $track = $this->tracks()->create([
            'title' => $details['title'],
            'photo' => $photoPath,
            'src' => $audioPath,
        ]);

        $track->tags()->attach($tags);

        return $track;
    }

    public function createPlaylist($details)
    {
        $photoPath = $details['photo']->store('playlists/photos', 'public');

        return $this->playlists()->create([
            'title' => $details['title'],
            'photo' => $photoPath,
        ]);
    }

    public function createAlbum($input)
    {
        $photoPath = $input['photo']->store('albums/photos', 'public');

        $input['details']['user_id'] = auth()->id();
        $input['details']['photo'] = $photoPath;

        $album = $this->albums()->create($input['details']);

        $tags = collect(explode(',', $input['tags']));      
        
        $tags = Tag::whereIn('id', $tags)->get();

        $album->tags()->attach($tags);

        $tracks = Track::publishedById($input['tracks'])->update([ 'album_id' => $album->id ]);

        return $album;
    }

    public function follow()
    {
        if (! $this->isFollowing()) {
            $this->followers()->attach(auth()->id());
        }

        return $this;
    }

    public function unfollow()
    {
        if ($this->isFollowing()) {
            auth()->user()->followings()->detach($this->id);
        }
    }

    protected function isFollowing()
    {
        $attributes = [ 'following_id' => $this->id ];

        return auth()->user()->followings()->where($attributes)->exists();
    }
}
