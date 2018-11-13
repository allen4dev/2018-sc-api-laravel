<?php

namespace App\Http;

class Responses {

  public static function format($type, $attributes = [], $statusCode = 200)
  {
      return response()->json([
          "data" => compact(['type', 'attributes']),
      ], $statusCode);
  }

  public static function unauthenticated() {
    $statusCode = 401;
    $title      = 'Unauthenticated';
    $detail     = 'This action is only allowed to authenticated members';

    return (new self)->createErrorResponse($statusCode, $title, $detail);
  }

  public static function modelNotFound($exception) {
    $model = explode('\\', $exception->getModel());
    $modelName = end($model);

    $statusCode = 404;
    $title      = 'Model not found';
    $detail     = "{$modelName} with that id does not exist";

    return (new self)->createErrorResponse($statusCode, $title, $detail);
  }

  public static function unauthorized() {
    $statusCode = 403;
    $title      = 'Forbidden';
    $detail     = 'You are not authorized to perform this action';

    return (new self)->createErrorResponse($statusCode, $title, $detail);
  }

  public static function notFound() {
    $statusCode = 404;
    $title      = 'Not Found';
    $detail     = 'The resource you are fetching does not exist';

    return (new self)->createErrorResponse($statusCode, $title, $detail);
  }

  private function createErrorResponse($statusCode, $title, $detail)
  {
      return response()->json([
        'errors' => [
            'status' => (string) $statusCode,
            'title'  => $title,
            'detail' => $detail
        ]
    ], $statusCode);
  }
}
