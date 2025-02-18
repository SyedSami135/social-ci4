<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */


    private $secretKey = "your_secret_key";

    public function before(RequestInterface $request,  $arguments = null)
    {

        $authHeader = $request->getHeaderLine('Authorization');


        if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
            return Services::response()->setStatusCode(401, 'Unauthorized')
                ->setJSON(['error' => 'No token provided or invalid format']);
        }


        $jwt = trim(substr($authHeader, 7));

        try {

            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));

            $request->userId = $decoded->id;
        } catch (\Exception $e) {

            return Services::response()->setStatusCode(401, 'Unauthorized')
                ->setJSON(['error' => $e->getMessage(),]);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
