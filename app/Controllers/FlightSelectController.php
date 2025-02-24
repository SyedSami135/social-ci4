<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\FlightService;
use CodeIgniter\HTTP\ResponseInterface;

class FlightSelectController extends BaseController
{
    public function index()
    {
        helper('XMLtoJSON');
        // Build a request object with selection parameters
        $req = (object)[
            'flightId' => '8d1523db-209b-11e9-b315-c86000c7b4c3',
            'results' => [
                '0e20b49d-1183-44aa-90a9-962284324419',
                'fc768206-0bfe-41bc-bf14-624f996f4a99'
            ]
        ];

        // // Example usage:
        // $data = [
        //     'BookingReference' => 'ABC123',
        //     'Status'           => 'Confirmed',
        //     'Expires'          => '2023-12-01T00:00:00',
        //     'PriceDetails'     => [
        //         'Currency'   => 'GBP',
        //         'TotalPrice' => '100.00',
        //         'BasePrice'  => '80.00',
        //         'Tax'        => '20.00'
        //     ],
        //     'Passengers' => [
        //         [
        //             'Type'        => 'adult',
        //             'Title'       => 'Mr',
        //             'FirstName'   => 'John',
        //             'LastName'    => 'Doe',
        //             'DateOfBirth' => '1980-01-01'
        //         ]
        //     ]
        // ];

        // return jsonToXml($jsonData);

        $selectionService = new FlightService();
        $result = $selectionService->selectFlight($req);
        return sendError(400, "some msg", $result,);
    }
}
