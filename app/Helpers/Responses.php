<?php

if (!function_exists('response_ok')) {
    /**
     * Return a json response with 200 http code.
     *
     * @param  string  $response_title
     * @param  string  $response_message
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     *
     */
    function response_ok(string $response_title = "", string $response_message = "", array $data = [], array $headers = [])
    {
        return response()->json([
            "response_code" => 200,
            "response_title" => $response_title,
            "response_message" => $response_message,
            "data" => $data
        ], 200)
            ->withHeaders($headers);
    }
}

if (!function_exists('response_ok_no_data')) {
    /**
     * Return a json response with 200 http code without data.
     *
     * @param  string  $response_title
     * @param  string  $response_message
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     *
     */
    function response_ok_no_data(string $response_title = "", string $response_message = "", array $headers = [])
    {
        return response()->json([
            "response_code" => 200,
            "response_title" => $response_title,
            "response_message" => $response_message,
        ], 200)
            ->withHeaders($headers);
    }
}

if (!function_exists('response_created')) {
    /**
     * Return a json response with 201 http code.
     *
     * @param  string  $response_title
     * @param  string  $response_message
     * @param  array  $data
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     *
     */
    function response_created(string $response_title = "", string $response_message = "", array $data = [], array $headers = [])
    {
        return response()->json([
            "response_code" => 201,
            "response_title" => $response_title,
            "response_message" => $response_message,
            "data" => $data
        ], 201)
            ->withHeaders($headers);
    }
}

if (!function_exists('response_invalid_request')) {
    /**
     * Return a json response with 400 http code. Used when the request inputs is incomplete or corrupted.
     *
     * @param  string  $response_title
     * @param  string  $response_message
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     *
     */
    function response_invalid_request(string $response_title = null, string $response_message = null, array $errors, array $headers = [])
    {
        $response_title = $response_title ?? __("messages.title.error");
        $response_message = $response_message ?? __("messages.body.error");

        return response()->json([
            "response_code" => 400,
            "response_title" => $response_title,
            "response_message" => $response_message,
            "errors" => $errors,
        ], 400)
            ->withHeaders($headers);
    }
}

if (!function_exists('response_unauthenticated')) {
    /**
     * Return a json response with 401 http code. Used when user is not logged in.
     *
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     *
     */
    function response_unauthenticated(array $headers = [])
    {
        saveCurrentUrl();

        return response()->json([
            "response_code" => 401,
            "response_title" => __("messages.title.not_authenticated"),
            "response_message" => __("messages.body.not_authenticated"),
            "redirectUrl" => route("login")
        ], 401)
            ->withHeaders($headers);
    }
}

if (!function_exists('response_forbidden')) {
    /**
     * Return a json response with 403 http code. Used when user is not allowed in.
     *
     * @param  string  $response_title
     * @param  string  $response_message
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     *
     */
    function response_forbidden(string $response_title = null, string $response_message = null, array $headers = [])
    {
        $response_title = $response_title ?? __("messages.title.forbidden");
        $response_message = $response_message ?? __("messages.body.forbidden");

        return response()->json([
            "response_code" => 403,
            "response_title" => $response_title,
            "response_message" => $response_message
        ], 403)
            ->withHeaders($headers);
    }
}

if (!function_exists('response_not_found')) {
    /**
     * Return a json response with 404 http code. Used when a resource is not found.
     *
     * @param  string  $response_title
     * @param  string  $response_message
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     *
     */
    function response_not_found(string $response_title = "", string $response_message = "", array $headers = [])
    {
        return response()->json([
            "response_code" => 404,
            "response_title" => $response_title,
            "response_message" => $response_message
        ], 404)
            ->withHeaders($headers);
    }
}

if (!function_exists('response_server_error')) {
    /**
     * Return a json response with 500 http code.
     *
     * @param  string  $response_title
     * @param  string  $response_message
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     *
     */
    function response_server_error(string $response_title = null, string $response_message = null, array $headers = [])
    {
        $response_title = $response_title ?? __("messages.title.error");
        $response_message = $response_message ?? __('messages.body.error');

        return response()->json([
            "response_code" => 500,
            "response_title" => $response_title,
            "response_message" => $response_message,
        ], 500)
            ->withHeaders($headers);
    }
}
