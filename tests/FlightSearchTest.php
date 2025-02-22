<?php

namespace Tests\Libraries;

use App\Libraries\FlightSearchService;
use CodeIgniter\Test\CIUnitTestCase;



class FlightSearchTest extends CIUnitTestCase
{

    public function testSearchFlightSuccess()
    {
        $flightService = new FlightSearchService();

        $req = (object)[
            'outboundDeparture' => 'LGW',
            'outboundDestination' => 'AMS',
            'outboundDate' => '2024-01-15', // Future date to avoid conflicts
            'inbound' => (object)[
                'departure' => 'AMS',
                'destination' => 'LGW',
                'date' => '2024-01-22' // Future date
            ],
        ];

        $result = $flightService->searchFlight($req);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('response', $result);
        $this->assertStringContainsString('<FlightSearchReq', $result['response']); // Basic XML check


    }


    public function testSearchFlightWithAirlines()
    {
        $flightService = new FlightSearchService();
        $req = (object)[
            // ... other required fields
            'airlines' => ['KL', 'BA'],
        ];

        $result = $flightService->searchFlight($req);
        $this->assertStringContainsString('<Airline>KL</Airline>', $result['response']);
        $this->assertStringContainsString('<Airline>BA</Airline>', $result['response']);
    }



    public function testSearchFlightWithOptions()
    {
        $flightService = new FlightSearchService();
        $req = (object)[
            // ... other required fields
            'options' => [
                'bagonlyfares' => 'true',
                'anotherOption' => 'someValue'
            ]
        ];
        $result = $flightService->searchFlight($req);

        $this->assertStringContainsString('<Option Type="bagonlyfares">true</Option>', $result['response']);
        $this->assertStringContainsString('<Option Type="anotherOption">someValue</Option>', $result['response']);
    }

    public function testSearchFlightWithPassengers()
    {
        $flightService = new FlightSearchService();
        $req = (object)[
            // ... other required fields ...
            'passengers' => [
                ['age' => 25],
                ['age' => 12]
            ],
        ];

        $result = $flightService->searchFlight($req);


        $this->assertStringContainsString('<Passenger Age="25"/>', $result['response']);
        $this->assertStringContainsString('<Passenger Age="12"/>', $result['response']);
    }

    public function testSearchFlightCurlError()
    {
        // Mock the storefront URL to an invalid one to force a cURL error.
        $flightService = new FlightSearchService();
        $flightService->storefrontUrl = 'http://invalid-url.example.com'; // Invalid URL

        $req = (object)[
            'outboundDeparture' => 'LGW',
            'outboundDestination' => 'AMS',
            'outboundDate' => '2024-01-29',
        ];

        $result = $flightService->searchFlight($req);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('Could not resolve host', $result['error']);
    }
}
