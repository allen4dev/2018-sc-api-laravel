<?php

namespace App\Http\Transformers;

use Illuminate\Support\Collection;

class IncludeTransformer {

    public static function loadRelationships($resource, $include)
    {
        if ($include) {
            $relationships = explode(',', $include);

            foreach($relationships as $relationship) {
                $resource->load($relationship);
            }
        }
        
        return $resource;
    }
    
    public static function includeData($resource, $include)
    {
        $requestedRelationships = explode(',', $include);
        
        $includes = collect();

        foreach ($requestedRelationships as $relationship)
        {
            if (! (new self)->hasRelationship($resource, $relationship))
            {
                continue;
            }

            tap($resource->$relationship, function ($relation) use ( & $includes )
            {
                if ($relation instanceof Collection) {
                    $includes = $includes->merge($relation);
                } else {
                    $includes->push($relation);
                }
            });
        }

        return $includes->unique();
    }

    public function hasRelationship($resource, $relationship)
    {
        return method_exists($resource, $relationship)
            && $resource->relationLoaded($relationship)
            && is_a($resource->$relationship(), "Illuminate\Database\Eloquent\Relations\Relation");
    }

}