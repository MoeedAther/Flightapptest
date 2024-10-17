<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Facebook\Facebook;
use Illuminate\Http\Request;
use App\Services\CloudflareStreamService;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

class VideoController extends Controller
{
    public function upload(Request $request, CloudflareStreamService $cloudflareStream)
    {

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $response = $cloudflareStream->uploadVideo($video);
            if (isset($response['videoId'])) {
                $videoId = $response['videoId'];
                $videoLink = $response['videoLink'];
            } else {
                return response()->json([
                    'error' => $response['error'],
                ], 400);
            }
        }
    }

    public function getVideos(CloudflareStreamService $cloudflareStream)
    {
        $videos = $cloudflareStream->listVideos();
        return response()->json($videos);
    }



    public function scheduleDelivery(CloudflareStreamService $cloudflareStream)
    {
        $streamingConfiguration = [
            'url' => 'https://watch.cloudflarestream.com/2e3fc42293d34af0b5ea8ddb6ee8738d',
            'stream_name' => 'youtube_stream',
            'stream_key' => 'kw5k-2v4d-x8d0-frgu-77te',
            'provider' => 'youtube',
            'rtmp_url' => 'rtmp://a.rtmp.youtube.com/live2',
        ];

        $response = $cloudflareStream->scheduleVideoDelivery('2e3fc42293d34af0b5ea8ddb6ee8738d', $streamingConfiguration);

        dd($response);

        return response()->json(['message' => 'Video delivery scheduled successfully']);
    }

    public function getStreamKeyAndUrl(Request $request)
    {
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_CLIENT_ID'),
            'app_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'default_graph_version' => 'v13.0',
        ]);

        // Get the OAuth2 client helper
        $helper = $fb->getRedirectLoginHelper();

        $loginUrl = $helper->getLoginUrl(env('FACEBOOK_REDIRECT_URL'));

        return redirect()->away($loginUrl);

        if (isset($_GET['code'])) {
            try {
                $accessToken = $helper->getAccessToken();
            } catch (FacebookResponseException $e) {
                echo 'Graph API returned an error: ' . $e->getMessage();
            } catch (FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
            }
        }

        dd();

        if (!isset($accessToken)) {
            $loginUrl = $helper->getLoginUrl(env('FACEBOOK_REDIRECT_URL'), ['user_video']);
            return redirect($loginUrl);
        }

        // Use the access token to make API calls
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_CLIENT_ID'),
            'app_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'default_graph_version' => 'v13.0',
        ]);

        try {
            $response = $fb->get('/me?fields=id,name,picture.width(200)', $accessToken);
            $user = $response->getGraphNode()->asArray();

            // Log the user's information
            dd($user);

            // Get the stream key and URL using the user's ID
            // ... (Implement your logic here)
        } catch (FacebookResponseException $e) {
            // Handle Graph API errors
            echo 'Graph API returned an error: ' . $e->getMessage();
        } catch (FacebookSDKException $e) {
            // Handle other errors
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        }
    }

    public function getStreamKeyUrl(Request $request)
    {
        try {
            $response = $request->all();
            dd($response);
        } catch (Exception $e) {
            Log::error('Error saving Facebook platform data: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing the request.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
