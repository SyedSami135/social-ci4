<?php

namespace App\Libraries;

class FlightSearchService
{
    public $storefrontUrl = 'https://abc.xyz.com';
    protected $clientId = '4128510421209355';
    protected $secret   = '097299568043040b93824bd7a965ff6f';
    /**
     * Execute the flight search.
     *
     * @param object $req A request object containing the search parameters.
     * @return array Result array with either 'response' or 'error'.
     */
    public function searchFlight($req)
    {
        // Build the endpoint URL.
        $endpoint = $this->storefrontUrl . '/api/v16/search/flight';

        // -----------------------------
        // Build the XML Request
        // -----------------------------
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><FlightSearchReq xmlns="http://vibe.travel"></FlightSearchReq>');

        // Profile Authentication (optional)
        if (!empty($req->profileUsername)) {
            $xml->addChild('ProfileUsername', $req->profileUsername);
        }
        if (!empty($req->profilePassword)) {
            $xml->addChild('ProfilePassword', $req->profilePassword);
        }

        // Outbound Flight (Required)
        $outbound = $xml->addChild('OutboundFlight');
        $outbound->addChild('Departure', $req->outboundDeparture ?? 'LGW');
        $outbound->addChild('Destination', $req->outboundDestination ?? 'AMS');
        $outbound->addChild('Date', $req->outboundDate ?? '2023-12-01');

        // Inbound Flight (Optional for round-trip)
        if (!empty($req->inbound)) {
            $inbound = $xml->addChild('InboundFlight');
            $inbound->addChild('Departure', $req->inbound->departure ?? 'AMS');
            $inbound->addChild('Destination', $req->inbound->destination ?? 'LGW');
            $inbound->addChild('Date', $req->inbound->date ?? '2023-12-08');
        }

        // Journey Type (Optional)
        $xml->addChild('JourneyType', $req->journeyType ?? 'R');

        // Direct Flight Flag (Optional)
        $xml->addChild('Direct', $req->direct ?? 'Y');

        // Airlines (Optional, expects an array)
        if (!empty($req->airlines) && is_array($req->airlines)) {
            $airlines = $xml->addChild('Airlines');
            foreach ($req->airlines as $airline) {
                $airlines->addChild('Airline', $airline);
            }
        }

        // Cabin Class (Optional)
        $xml->addChild('CabinClass', $req->cabinClass ?? 'E');

        // Passengers (Optional, expects an array of arrays/objects)
        if (!empty($req->passengers) && is_array($req->passengers)) {
            $passengers = $xml->addChild('Passengers');
            foreach ($req->passengers as $passenger) {
                $p = $passengers->addChild('Passenger');
                if (!empty($passenger['age'])) {
                    $p->addAttribute('Age', $passenger['age']);
                }
            }
        }

        // Options (Optional, expects an associative array)
        if (!empty($req->options) && is_array($req->options)) {
            $options = $xml->addChild('Options');
            foreach ($req->options as $optionType => $optionValue) {
                $opt = $options->addChild('Option', $optionValue);
                $opt->addAttribute('Type', $optionType);
            }
        }

        // Convert the XML object to a string payload
        $xmlPayload = $xml->asXML();


        // Base64 encode the "ClientID:Secret" string.
        $credentials = base64_encode($this->clientId . ':' . $this->secret);


        // Prepare headers including the Authorization header.
        $headers = [
            'Content-Type: application/xml',
            'Content-Length: ' . strlen($xmlPayload),
            'Authorization: Basic ' . $credentials
        ];

        // -----------------------------
        // Send the Request via cURL
        // -----------------------------
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['error' => $error];
        }

        curl_close($ch);

        return $response;
        return $this->parseFlightSearchResponse($response);
    }


    protected function parseFlightSearchResponse($xmlResponse)
    {
        // Parse the XML response, including CDATA sections.
        $xml = simplexml_load_string($xmlResponse, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml === false) {
            // In case of parsing errors, collect error messages.
            $errors = libxml_get_errors();
            libxml_clear_errors();
            return ['error' => 'Failed to parse XML response'];
        }

        // Initialize the result array with root attributes.
        $result = [
            'TotalBookingOptions' => (string) $xml['TotalBookingOptions'],
            'Currency'            => (string) $xml['Currency'],
            'CardCharges'         => [],
            'AllResultsURL'       => (string) $xml->AllResultsURL,
            'Flights'             => []
        ];

        // Parse CardCharges if available.
        if (isset($xml->CardCharges->CardFee)) {
            foreach ($xml->CardCharges->CardFee as $cardFee) {
                $result['CardCharges'][] = [
                    'Card'      => (string) $cardFee['Card'],
                    'Type'      => (string) $cardFee['Type'],
                    'MaxCharge' => (string) $cardFee['MaxCharge'],
                    'Currency'  => (string) $cardFee['Currency'],
                    'Fee'       => trim((string) $cardFee)
                ];
            }
        }

        // Parse Flight elements.
        if (isset($xml->Flight)) {
            foreach ($xml->Flight as $flight) {
                $flightData = [
                    'ID'       => (string) $flight['ID'],
                    'DeepLink' => (string) $flight->DeepLink,
                    'Results'  => [],
                    'OutboundLegs' => [],
                    'InboundLegs'  => []
                ];

                // Parse the Results element.
                if (isset($flight->Results)) {
                    foreach ($flight->Results->Result as $resultElement) {
                        $flightData['Results'][] = (string) $resultElement;
                    }
                }

                // Parse OutboundLegs.
                if (isset($flight->OutboundLegs)) {
                    foreach ($flight->OutboundLegs->Leg as $leg) {
                        $legData = [
                            'ID'                     => (string) $leg['ID'],
                            'DepartureAirportCode'   => (string) $leg->DepartureAirportCode,
                            'DepartureAirportName'   => (string) $leg->DepartureAirportName,
                            'DestinationAirportCode' => (string) $leg->DestinationAirportCode,
                            'DestinationAirportName' => (string) $leg->DestinationAirportName,
                            'DepartureDate'          => (string) $leg->DepartureDate,
                            'DepartureTime'          => (string) $leg->DepartureTime,
                            'ArrivalDate'            => (string) $leg->ArrivalDate,
                            'ArrivalTime'            => (string) $leg->ArrivalTime,
                            'AirlineCode'            => (string) $leg->AirlineCode,
                            'AirlineName'            => (string) $leg->AirlineName,
                            'CabinClass'             => (string) $leg->CabinClass,
                            'Class'                  => (string) $leg->Class,
                            'NumberOfStops'          => (string) $leg->NumberOfStops,
                            'FlightNumber'           => (string) $leg->FlightNumber,
                            'FareType'               => (string) $leg->FareType,
                            'BaggageAllowance'       => [],
                            'PriceDetails'           => []
                        ];

                        // Parse BaggageAllowance.
                        if (isset($leg->BaggageAllowance)) {
                            foreach ($leg->BaggageAllowance->Bag as $bag) {
                                $legData['BaggageAllowance'][] = [
                                    'PaxType' => (string) $bag['PaxType'],
                                    'Type'    => (string) $bag['Type'],
                                    'Units'   => (string) $bag['Units'],
                                    'Value'   => trim((string) $bag)
                                ];
                            }
                        }

                        // Parse PriceDetails.
                        if (isset($leg->PriceDetails)) {
                            $priceDetails = [
                                'Currency'       => (string) $leg->PriceDetails->Currency,
                                'PriceBreakdown' => []
                            ];
                            if (isset($leg->PriceDetails->PriceBreakdown)) {
                                foreach ($leg->PriceDetails->PriceBreakdown->Passenger as $passenger) {
                                    $priceDetails['PriceBreakdown'][] = [
                                        'Type'                   => (string) $passenger['Type'],
                                        'Quantity'               => (string) $passenger->Quantity,
                                        'BasePrice'              => (string) $passenger->BasePrice,
                                        'TotalPriceBeforeDiscount' => (string) $passenger->TotalPriceBeforeDiscount,
                                        'Tax'                    => (string) $passenger->Tax,
                                        'ProfileMarkUp'          => (string) $passenger->ProfileMarkUp
                                    ];
                                }
                            }
                            $legData['PriceDetails'] = $priceDetails;
                        }
                        $flightData['OutboundLegs'][] = $legData;
                    }
                }

                // Parse InboundLegs if available.
                if (isset($flight->InboundLegs)) {
                    foreach ($flight->InboundLegs->Leg as $leg) {
                        $legData = [
                            'ID'                     => (string) $leg['ID'],
                            'DepartureAirportCode'   => (string) $leg->DepartureAirportCode,
                            'DepartureAirportName'   => (string) $leg->DepartureAirportName,
                            'DestinationAirportCode' => (string) $leg->DestinationAirportCode,
                            'DestinationAirportName' => (string) $leg->DestinationAirportName,
                            'DepartureDate'          => (string) $leg->DepartureDate,
                            'DepartureTime'          => (string) $leg->DepartureTime,
                            'ArrivalDate'            => (string) $leg->ArrivalDate,
                            'ArrivalTime'            => (string) $leg->ArrivalTime,
                            'AirlineCode'            => (string) $leg->AirlineCode,
                            'AirlineName'            => (string) $leg->AirlineName,
                            'CabinClass'             => (string) $leg->CabinClass,
                            'Class'                  => (string) $leg->Class,
                            'NumberOfStops'          => (string) $leg->NumberOfStops,
                            'FlightNumber'           => (string) $leg->FlightNumber,
                            'FareType'               => (string) $leg->FareType,
                            'BaggageAllowance'       => [],
                            'PriceDetails'           => []
                        ];

                        if (isset($leg->BaggageAllowance)) {
                            foreach ($leg->BaggageAllowance->Bag as $bag) {
                                $legData['BaggageAllowance'][] = [
                                    'PaxType' => (string) $bag['PaxType'],
                                    'Type'    => (string) $bag['Type'],
                                    'Units'   => (string) $bag['Units'],
                                    'Value'   => trim((string) $bag)
                                ];
                            }
                        }

                        if (isset($leg->PriceDetails)) {
                            $priceDetails = [
                                'Currency'       => (string) $leg->PriceDetails->Currency,
                                'PriceBreakdown' => []
                            ];
                            if (isset($leg->PriceDetails->PriceBreakdown)) {
                                foreach ($leg->PriceDetails->PriceBreakdown->Passenger as $passenger) {
                                    $priceDetails['PriceBreakdown'][] = [
                                        'Type'                   => (string) $passenger['Type'],
                                        'Quantity'               => (string) $passenger->Quantity,
                                        'BasePrice'              => (string) $passenger->BasePrice,
                                        'TotalPriceBeforeDiscount' => (string) $passenger->TotalPriceBeforeDiscount,
                                        'Tax'                    => (string) $passenger->Tax,
                                        'ProfileMarkUp'          => (string) $passenger->ProfileMarkUp
                                    ];
                                }
                            }
                            $legData['PriceDetails'] = $priceDetails;
                        }
                        $flightData['InboundLegs'][] = $legData;
                    }
                }
                $result['Flights'][] = $flightData;
            }
        }

        return $result;
    }
}
