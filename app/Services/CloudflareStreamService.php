<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class CloudflareStreamService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.cloudflare.com/client/v4/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('CLOUDFLARE_STREAM_API_KEY'),
                'X-Auth-Email' => env('CLOUDFLARE_STREAM_EMAIL'),
            ],
        ]);
    }

    public function uploadVideo($video)
    {
        try {
            $videoData = [
                [
                    'name' => 'file',
                    'filename' => $video->getClientOriginalName(),
                    'MimeType' => $video->getClientMimeType(),
                    'contents' => fopen($video->getPathname(), 'r'),
                ]
            ];
            $response = $this->client->request('POST', 'accounts/ddf78c27a1bcbe5e20d7837add225979/stream', [
                'multipart' => $videoData,
            ]);
            $responseBody = json_decode($response->getBody(), true);
            if (isset($responseBody['result']['duration'])) {
                $durationInSeconds = $responseBody['result']['duration'];
                $formattedDuration = gmdate("H:i:s", $durationInSeconds); // Format to H:i:s
                dd($formattedDuration);
            } else {
                $formattedDuration = '00:00:00';
                dd($formattedDuration);
            }
            dd($responseBody);
            if ($responseBody['success']) {
                $videoId = $responseBody['result']['uid'];
                $videoLink = "https://watch.cloudflarestream.com/$videoId";
                return [
                    'videoId' => $videoId,
                    'videoLink' => $videoLink,
                ];
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = (string) $response->getBody();
            return response()->json([
                'error' => 'Client error occurred',
                'message' => $e->getMessage(),
                'details' => $responseBodyAsString,
            ], 400);
        } catch (\Exception $e) {
            dd('Errorrrr ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function listVideos()
    {
        $response = $this->client->request('GET', 'accounts/ddf78c27a1bcbe5e20d7837add225979/stream');

        return json_decode($response->getBody(), true);
    }

    public function scheduleVideoDelivery($videoId, $streamingConfiguration)
    {
        try {
            $response = $this->client->request('POST', "accounts/2e3fc42293d34af0b5ea8ddb6ee8738d/videos/{$videoId}/streams", [
                'json' => $streamingConfiguration,
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = (string) $response->getBody();
            return response()->json([
                'error' => 'Client error occurred',
                'message' => $e->getMessage(),
                'details' => $responseBodyAsString,
            ], 400);
        }

        // $statusCode = $response->getStatusCode();

        // if ($statusCode === 201) {
        //     // Video delivery scheduled successfully
        //     $streamingUrl = $response->getHeader('Location')[0];
        //     return $streamingUrl;
        // } else {
        //     // Handle errors
        //     $errorMessage = json_decode($response->getBody()->getContents(), true)['errors'][0]['message'];
        //     throw new Exception("Failed to schedule video delivery: {$errorMessage}");
        // }
    }
}
