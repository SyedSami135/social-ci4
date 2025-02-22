<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\FlightSearchService;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\JsonResponse;
use PHPUnit\Util\Json;

class FlightSearchController extends BaseController
{
    public function index()
    {


        // Build a request object with search parameters.
        $req = (object)[
            'profileUsername'     => 'user123',
            'profilePassword'     => 'pass123',
            'outboundDeparture'   => 'LGW',
            'outboundDestination' => 'AMS',
            'outboundDate'        => '2023-12-01',
            'inbound'             => (object)[
                'departure'   => 'AMS',
                'destination' => 'LGW',
                'date'        => '2023-12-08'
            ],
            'journeyType'         => 'R',
            'direct'              => 'Y',
            'airlines'            => ['BA', 'AA'],
            'cabinClass'          => 'E',
            'passengers'          => [
                ['age' => 30],
                ['age' => 10]
            ],
            'options'             => [
                'bagonlyfares' => 'true'
            ]
        ];

        // return sendResponse($req, "some msg");


        // Use the FlightSearchService library.
        $flightService = new FlightSearchService();
        $result = $flightService->searchFlight($req);


        echo "result:";
        return sendError(400, "some msg",$result,);
        // return $this->respond($result, 200);
        // Return the result as JSON.
        // return $result;
    }

    protected function respond($data, $status = Response::HTTP_OK, array $headers = [])
    {
        return new Response($data, $status, $headers);
    }
}
