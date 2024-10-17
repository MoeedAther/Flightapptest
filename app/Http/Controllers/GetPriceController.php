<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;

class GetPriceController extends Controller
{
    public function __invoke(Client $client)
    {
        $url = 'https://test.api.amadeus.com/v1/shopping/flight-offers/pricing';
        $access_token = 'Yi3VSZlPsj3tzb2cExu5Qn4GL5HA';
        $data = [
            'data' => [
                'type' => 'flight-offers-pricing',
                'flightOffers' => [
                    [
                        "type" => "flight-offer",
                        "id" =>  "1",
                        "source" =>  "GDS",
                        "instantTicketingRequired" => false,
                        "nonHomogeneous" => false,
                        "oneWay" => false,
                        "isUpsellOffer" => false,
                        "lastTicketingDate" => "2024-08-06",
                        "lastTicketingDateTime" => "2024-08-06",
                        "numberOfBookableSeats" => 9,
                        'itineraries' => [
                            [
                                'duration' => 'PT10H10M',
                                'segments' => [
                                    [
                                        'departure' => [
                                            'iataCode' => 'BOS',
                                            'terminal' => 'E',
                                            'at' => '2024-08-06T18:25:00'
                                        ],
                                        'arrival' => [
                                            'iataCode' => 'KEF',
                                            'at' => '2024-08-07T06:00:00'
                                        ],
                                        'carrierCode' => 'FI',
                                        'number' => '630',
                                        'aircraft' => [
                                            'code' => '76W'
                                        ],
                                        '[operating]' => [
                                            'carrierCode' => 'FI'
                                        ],
                                        'duration' => 'PT5H20M',
                                        'id' => '35',
                                        'numberOfStops' => '0',
                                        'blacklistedInEU' => false
                                    ],
                                    [
                                        'departure' => [
                                            'iataCode' => 'KEF',
                                            'at' => '2024-08-07T07:40:00'
                                        ],
                                        'arrival' => [
                                            'iataCode' => 'CDG',
                                            'terminal' => '2B',
                                            'at' => '2024-08-07T11:05:00'
                                        ],
                                        'carrierCode' => 'FI',
                                        'number' => '542',
                                        'aircraft' => [
                                            'code' => '76W'
                                        ],
                                        'operating' => [
                                            'carrierCode' => 'FI'
                                        ],
                                        'duration' => 'PT3H25M',
                                        'id' => '36',
                                        'numberOfStops' => '0',
                                        'blacklistedInEU' => false
                                    ],
                                ]
                            ]
                        ],
                        'validatingAirlineCodes' => [
                            'FI'
                        ],
                        'travelerPricings' => [
                            [
                                'travelerId' => '1',
                                'fareOption' => 'STANDARD',
                                'travelerType' => 'ADULT',
                                'price' => [
                                    'currency' => 'EUR',
                                    'total' => '482.55',
                                    'base' => '363.00'
                                ],
                                'fareDetailsBySegment' => [
                                    [
                                        'segmentId' => '35',
                                        'cabin' => 'ECONOMY',
                                        'fareBasis' => 'VJ1QUSLT',
                                        'class' => 'V',
                                        'includedCheckedBags' => [
                                            'quantity' => 0
                                        ]
                                    ],
                                    [
                                        'segmentId' => '36',
                                        'cabin' => 'ECONOMY',
                                        'fareBasis' => 'VJ1QUSLT',
                                        'brandedFare' => 'LIGHT',
                                        'class' => 'V',
                                        'includedCheckedBags' => [
                                            'quantity' => 0
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],
                'json' => $data
            ]);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            return $exception->getMessage();
        }
    }
}
