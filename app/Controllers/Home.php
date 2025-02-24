<?php

namespace App\Controllers;

use App\Models\FlightDestination;

define('PATH', 'http://localhost/umrahfuras.com/restfull-api/');

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }


    public function home()
    {
        return view('posts-view');
    }


    public function getFlights()
    {



        $response = array();

        $image = [
            PATH . 'images/Task-000719/dubai.webp',
            PATH . 'images/Task-000719/Jeddah.webp',
            PATH . 'images/Task-000719/istanbul.webp',
            PATH . 'images/Task-000719/doha.webp',
            PATH . 'images/Task-000719/paris.webp',
            PATH . 'images/Task-000719/london.webp',
            PATH . 'images/Task-000719/newyork.webp',
        ];

        $slug = [
            "cheap-flights-from-islamabad-to-karachi",
            "cheap-flights-from-karachi-to-islamabad",
            "cheap-flights-from-islamabad-to-lahore",
            "cheap-flights-from-islamabad-to-faisalabad",
            "cheap-flights-from-islamabad-to-multan",
            "cheap-flights-from-islamabad-to-quetta",
            "cheap-flights-from-islamabad-to-sialkot",
            "cheap-flights-from-islamabad-to-peshawar",
        ];

        $title = [
            "Flights to Karachi",
            "Flights to Islamabad",
            "Flights to Lahore",
            "Flights to Faisalabad",
            "Flights to Multan",
            "Flights to Quetta",
            "Flights to Sialkot",
            "Flights to Peshawar",
        ];

        $status = ['In-Air', 'Cancelled', 'Delayed'];

        $meaning = [
            'Flight is airborne (on-route).',
            'The flight has been cancelled due to unavoidable circumstances.',
            'The scheduled flight is postponed.',
        ];

        $price = [58488, 78924, 94780, 54887, 247402, 260519, 394076];
        $cities = ['DUBAI', 'JEDDAH', 'ISTANBUL', 'DOHA', 'PARIS', 'LONDON', 'NEW YORK'];
        $code = ['DXB', 'JED', 'IST', 'DOH', 'CDG', 'LHR', 'JFK'];
        $records = [];
        for ($i = 0; $i < 7; $i++) {
            $records[$i] = [
                'imageURI' => $image[$i],
                'slug'     => $slug[$i],
                'title'    => "Flight Title for " . $cities[$i],  // Add this line$title[$i],
                'cities'     => $cities[$i],
                'code'     => $code[$i],
                'price'    => $price[$i],

            ];
        }
        $bulkData = [
            [
                'imageURI' => 'http://localhost/umrahfuras.com/restfull-api/images/Task-000719/dubai.webp',
                'slug'     => 'cheap-flights-from-islamabad-to-karachi',
                'title'    => 'Flight Title for DUBAI',
                'cities'   => 'DUBAI',
                'code'     => 'DXB',
                'price'    => 58488
            ],
            [
                'imageURI' => 'http://localhost/umrahfuras.com/restfull-api/images/Task-000719/Jeddah.webp',
                'slug'     => 'cheap-flights-from-karachi-to-islamabad',
                'title'    => 'Flight Title for JEDDAH',
                'cities'   => 'JEDDAH',
                'code'     => 'JED',
                'price'    => 78924
            ],
            [
                'imageURI' => 'http://localhost/umrahfuras.com/restfull-api/images/Task-000719/istanbul.webp',
                'slug'     => 'cheap-flights-from-islamabad-to-lahore',
                'title'    => 'Flight Title for ISTANBUL',
                'cities'   => 'ISTANBUL',
                'code'     => 'IST',
                'price'    => 94780
            ],
            [
                'imageURI' => 'http://localhost/umrahfuras.com/restfull-api/images/Task-000719/doha.webp',
                'slug'     => 'cheap-flights-from-islamabad-to-faisalabad',
                'title'    => 'Flight Title for DOHA',
                'cities'   => 'DOHA',
                'code'     => 'DOH',
                'price'    => 54887
            ],
            [
                'imageURI' => 'http://localhost/umrahfuras.com/restfull-api/images/Task-000719/paris.webp',
                'slug'     => 'cheap-flights-from-islamabad-to-multan',
                'title'    => 'Flight Title for PARIS',
                'cities'   => 'PARIS',
                'code'     => 'CDG',
                'price'    => 247402
            ],
            [
                'imageURI' => 'http://localhost/umrahfuras.com/restfull-api/images/Task-000719/london.webp',
                'slug'     => 'cheap-flights-from-islamabad-to-quetta',
                'title'    => 'Flight Title for LONDON',
                'cities'   => 'LONDON',
                'code'     => 'LHR',
                'price'    => 260519
            ],
            [
                'imageURI' => 'http://localhost/umrahfuras.com/restfull-api/images/Task-000719/newyork.webp',
                'slug'     => 'cheap-flights-from-islamabad-to-sialkot',
                'title'    => 'Flight Title for NEW YORK',
                'cities'   => 'NEW YORK',
                'code'     => 'JFK',
                'price'    => 394076
            ]
        ];
        // return $this->response->setJSON($records,); $records;
        $flightModel = new FlightDestination();

        $result = $flightModel->insertBatch( $bulkData);


        if ($result !== false) {
            echo "Inserted {$result} records successfully.";
        } else {
            echo "Error inserting records.";
        }

        $cities_slug = [];

        foreach ($cities as $cities_name) {
            $cities_slug[] = strtolower(str_replace(' ', '-', $cities_name));
        }

        for ($i = 0; $i < 7; $i++) {
            $a = $i + 1;
            $destinations[] = [
                'id' => $a,
                'slug' => $cities_slug[$i],
                'code' => $code[$i],
                'city' => $cities[$i],
                'price' => $price[$i],
                'curr_sign' => 'PKR',
                'image' => $image[$i],
            ];
        }

        for ($i = 0; $i < 8; $i++) {
            $a = $i + 1;
            $destination[] = [
                'id' => $a,
                'slug' => $slug[$i],
                'title' => $title[$i],
            ];
        }

        for ($i = 0; $i < 3; $i++) {
            $a = $i + 1;
            $table[] = [
                'id' => $a,
                'status' => $status[$i],
                'meaning' => $meaning[$i],
            ];
        }

        $response = [
            'destinations' => @$destinations,
            'cheap_flights' => [
                'heading' => 'Fly Smart, Save Big with TripPlannerPK!',
                'description' => 'With tripplannerpk.com you can fly with Ease and Convenience!
                    We are introducing our  e-ticketing service, which is the quickest and easiest method to book flights. Bid farewell to long lines and airport wait periods. You can schedule a flight using tripplannerpk.com from the convenience of your home or workplace at any time of day.
                     You can quickly select your chosen flight, pick your seat, and securely purchase your flight ticket with just a few clicks. Your E-ticket will be emailed to you immediately, so you would not have to worry about paper tickets or losing them. Moreover, tripplannerpk.com offers multiple discounts on your flight tickets because we think that travel should be accessible to everyone.By using our site to find cheap airline tickets you can cut your travel costs.
                    Additionally, our E-ticketing service makes it simple to make changes to your reservation, allowing you to easily alter your travel arrangements. Additionally, our helpful customer service team is always accessible to help if you have any questions or concerns.
                    Our goal is to make your trip hassle-free. When you choose us, you put all of your travel-related concerns in our capable hands.Your time and money are both saved by us. Opt for tripplannerpk to travel without any worries.
                    ',
            ],
            'flight_status' => [
                'heading' => 'Flight Status',
                'description' => 'Are you tired of  spending hours at the airport waiting for delayed or cancelled flights? Checking the status of your Air tickets with us is a simple option provided by our website tripplannerpk.com. You can keep track of the arrival and departure times, gate details, and any schedule modifications with our real-time updates. Our intuitive interface enables you to quickly obtain the data you require so that you can make the necesPKRy travel plans. Check your flights tickets status with us right away to avoid having your journey plans ruined by unforeseen flight delays.',
                'table' => @$table,
            ],
            'flight_schedule' => [
                'heading' => 'Flight Schedule',
                'description' => 'Looking for a simple and convenient way to monitor your Flight schedule? Look nowhere else! For all main airlines, our website offers the most recent arrival and departure information. You can easily look for your trip using our user-friendly interface by airline, flight number, or destination airport. In order for you to remain aware and make informed decisions, we offer real-time updates on delays, cancellations, and gate changes. Do not be caught off guard by sudden changes to your flight plan. You can rely on our website to inform and prepare you for your upcoming travels.
                    Additionally, we offer details regarding upcoming flight availability so that you can simply pre-book your flight tickets with tripplannerpk.com at a discount.
                    By looking at the travel schedules for a few of the best Pakistani airlines, you can find affordable flights to Pakistan.
                    ',
            ],
            'cheap_flights_price' => [
                'heading' => 'Cheap Flights Tickets Price',
                'description' => 'The most affordable ticket prices are available at tripplannerpk.com for flights to almost all locations all over the globe.Whether you are travelling for pleasure, work, or to see friends and family, we have the discounts you need to get there affordably. You can easily find and compare prices from all the major airlines using our website tripplannerpk.com which allows you to select the trip that best suits your needs both financially and logistically.Book your cheap flight tickets right away to begin making plans for your upcoming journey.
                    .Here are a few of the most popular routes and their beginning prices on tripplannerpk.com
                    ',
            ],
            'domestic_flights' => [
                'title' => "Domestic Flights",
                'destinations' =>
                [
                    "Karachi to Quetta",
                    "Karachi to Islamabad",
                    "Karachi to Lahore",
                    "Karachi to Peshawar",
                    "Karachi to Faisalabad",
                ],
            ],
            'international_flights' => [
                'title' => "International Flights",
                'description' => 'The following are a few of the most popular international flight routes tripplannerpk.com offers:',
                'destinations' =>
                [
                    "Karachi to Dubai",
                    "Lahore to Dubai",
                    "Islamabad to Dubai",
                    "Lahore to Riyadh",
                    "Karachi to Jeddah",
                ],
            ],
            'top_airlines' => [
                [
                    'title' => "Top Airlines",
                    'description' => "The following are some of the major domestic airlines that have partnered with tripplannerpk.com:",
                    'flights' =>
                    [
                        "Airblue",
                        "AirSial",
                        "PIA",
                        "K2 Airways",
                        "Serene Airways",
                    ],
                ],
                [
                    'title' => "On tripplannerpk.com, some of the most well-known foreign airlines include:",
                    'flights' =>
                    [
                        "Air Arabia",
                        "Emirates",
                        "Etihad Airways",
                        "Oman Air",
                        "Turkish Airlines",
                    ],
                ],
            ],
            'web_check_in' => [
                'title' => 'Easy Online Check-In with TripPlannerPK',
                'description' => "Taking a flight is usually  troubling and draining. You're either waiting impatiently because you came early or rushing to make your flight. Web check-in is a fantastic option if you're looking for a method to skip a portion of the tedious airport process.
                    However, you should be conscious of a few additional things. Even though you checked in online, you still have to turn up for boarding. You will also  need to check in your bags. When determining the ideal arrival time at the airport, bear flight security inspections in mind as well. The same rules apply if you want to check any unusual goods in your luggage.Even though online check in  can save you time, you should be conscious of these other requirements before boarding the aircraft. Use tripplannerpk.com to check your ticket status easily and avoid any last minute hassle.",
                'flight_to_top_cities' =>
                [
                    'heading' => "Flights To Top Cities",
                    'destination' => @$destination,
                ],

                'top_international_destinations' =>
                [
                    'heading' => "Flights To Top International Destinations",
                    'destination' =>
                    [
                        "Flights to Jeddah",
                        "Flights to London",
                        "Flights to New York",
                        "Flights to Paris",
                        "Flights to Dubai",
                        "Flights to Toronto",
                        "Flights to Sydney",
                        "Flights to Singapore",
                    ],
                ],
            ],
        ];

        return $this->response->setJSON($response);
    }
}
