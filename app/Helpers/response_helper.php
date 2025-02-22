<?php

if (!function_exists('sendResponse')) {
    function sendResponse($data = [], string $message = "Success", int $statusCode = 200)
    {
        return service('response')->setStatusCode($statusCode)
            ->setJSON([
                'status'  => $statusCode,
                'success' => true,
                'message' => $message,
                'data'    => $data
            ]);
    }
}

if (!function_exists('sendError')) {
    function sendError(int $statusCode = 500,string $message = "Something went wrong",  $errors = [])
    {
        return service('response')->setStatusCode($statusCode)
            ->setJSON([
                'status'  => $statusCode,
                'success' => false,
                'message' => $message,
                'errors'  => $errors
            ]);
    }
}
