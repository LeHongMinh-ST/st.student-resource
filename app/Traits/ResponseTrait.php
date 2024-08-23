<?php

declare(strict_types=1);

namespace App\Traits;

use App\Domain\Authentication\Values\AuthValues;
use App\Enums\AuthApiSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    /**
     * Returns a JSON response with the given data, status, headers, and options.
     *
     * @param  mixed  $data  The data to be included in the JSON response.
     * @param  int  $status  The HTTP status code (default is 200 OK).
     * @param  array  $headers  Additional headers for the response.
     * @param  int  $options  JSON encoding options.
     * @return JsonResponse The JSON response.
     */
    public function json(mixed $data, int $status = Response::HTTP_OK, array $headers = [], int $options = 0): JsonResponse
    {
        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Returns a JSON response indicating successful deletion.
     *
     * @param  Model|null  $deletedModel  The deleted model, if available.
     * @return JsonResponse The JSON response.
     */
    public function deleted(?Model $deletedModel = null): JsonResponse
    {
        // If no model is provided, return an accepted response
        if (! $deletedModel) {
            return $this->accepted();
        }

        // Get the ID and class name of the deleted model
        $id = $deletedModel->getKey();
        $className = (new ReflectionClass($deletedModel))->getShortName();

        // Return an accepted response with a deletion success message
        return $this->accepted([
            'message' => "{$className} ({$id}) Deleted Successfully.",
        ]);
    }

    /**
     * Returns a JSON response indicating successful creation.
     *
     * @param  mixed|null  $data  The data to be included in the response.
     * @param  int  $status  The HTTP status code (default is 201 Created).
     * @param  array  $headers  Additional headers for the response.
     * @param  int  $options  JSON encoding options.
     * @return JsonResponse The JSON response.
     */
    public function created(
        mixed $data = null,
        int $status = Response::HTTP_CREATED,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Returns a JSON response indicating accepted status.
     *
     * @param  mixed|null  $data  The data to be included in the response.
     * @param  int  $status  The HTTP status code (default is 202 Accepted).
     * @param  array  $headers  Additional headers for the response.
     * @param  int  $options  JSON encoding options.
     * @return JsonResponse The JSON response.
     */
    public function accepted(
        mixed $data = null,
        int $status = Response::HTTP_ACCEPTED,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Returns a JSON response with no content.
     *
     * @param  int  $status  The HTTP status code (default is 204 No Content).
     * @return JsonResponse The JSON response.
     */
    public function noContent(int $status = Response::HTTP_NO_CONTENT): JsonResponse
    {
        return new JsonResponse(null, $status);
    }

    /**
     * Returns a JSON response with authentication token details.
     *
     * @param  AuthValues  $authValues  The authentication values containing tokens and expiration.
     * @param  AuthApiSection  $session  The authentication session section.
     * @return JsonResponse The JSON response.
     */
    public function responseWithToken(AuthValues $authValues, AuthApiSection $session): JsonResponse
    {
        return $this->json([
            'section' => $session,
            'token_type' => 'Bearer',
            'access_token' => $authValues->getAccessToken(),
            'refresh_token' => $authValues->getRefreshToken(),
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
