<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;

class FlightSearchController extends Controller
{
    public function __invoke(Request $request, Client $client)
    {
        $url = 'https://test.api.amadeus.com/v2/shopping/flight-offers';
        $access_token = 'Yi3VSZlPsj3tzb2cExu5Qn4GL5HA';
        $data = [
            'originDestinations' => [
                [
                    'id' => 1,
                    'originLocationCode' => 'BOS',
                    'destinationLocationCode' => 'PAR',
                    'departureDateTimeRange' => [
                        'date' => '2024-08-06'
                    ]
                ]
            ],
            'travelers' => [
                [
                    'id' => 1,
                    'travelerType' => 'ADULT'
                ]
            ],
            'sources' => [
                'GDS'
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
            $responseBody = json_decode($response->getBody(), true);
            return response()->json($responseBody, 200);
        } catch (GuzzleException $exception) {
            dd($exception);
        }
    }
}
