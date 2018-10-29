<?php

namespace App\Http;

class Responses
{
  public static function format($type, $attributes = [], $status = 200)
  {
    return response()->json([
      'data' => [
          'type' => $type,
          'attributes' => $attributes,
      ]
    ], $status);
  }
}
