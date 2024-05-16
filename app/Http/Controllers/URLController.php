<?php

namespace App\Http\Controllers;

use App\Models\URL;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class URLController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
        ]);

        $apiKey = config('services.google_safe_browsing.key');

        $url = 'https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . $apiKey;

        try {
            $response = Http::post($url, [
                'threatInfo' => [
                    'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                    'platformTypes' => ['ANY_PLATFORM'],
                    'threatEntryTypes' => ['URL'],
                    'threatEntries' => [
                        ['url' => $request->original_url]
                    ]
                ]
            ]);

            if ($response->successful() && !empty($response['matches'])) {
                return response()->json(['error' => 'The URL is not safe'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while checking the URL safety'], 500);
        }

        $existingUrl = URL::where('original_url', $request->original_url)->first();
        if ($existingUrl) {
            return response()->json(['short_url' => $existingUrl->short_url]);
        }

        $shortUrl = Str::random(6);
        $newUrl = URL::create([
            'original_url' => $request->original_url,
            'short_url' => $shortUrl
        ]);

        return response()->json(['short_url' => $newUrl->short_url]);
    }

    public function show($shortUrl)
    {
        $url = URL::where('short_url', $shortUrl)->firstOrFail();

        return redirect($url->original_url);
    }
}
