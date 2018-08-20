<?php

namespace Zcwilt\Api\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;

class AbstractApiController extends Controller
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): AbstractApiController
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    protected function respond(array $data, array $headers = []): JsonResponse
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param mixed $message array || string
     * @return JsonResponse
     */
    protected function respondWithError($message): JsonResponse
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }
}
