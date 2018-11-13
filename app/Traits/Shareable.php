<?php

namespace App\Traits;

use App\Shared;

trait Shareable
{
    public function shared()
    {
        return $this->morphMany(Shared::class, 'shared');
    }

    public function share()
    {
        $attributes = [ 'user_id' => auth()->id(), 'type' => $this->getSharedType() ];

        if (! $this->isShared($attributes)) {
            $this->shared()->create($attributes);
        }

        return $this;
    }

    protected function isShared($attributes)
    {
        return $this->shared()->where($attributes)->exists();
    }

    protected function getSharedType()
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }
}
