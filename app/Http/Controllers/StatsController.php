<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class StatsController
{

    public function index() {
        $snapshot = cache()->get('stats:snapshot:latest');

        if (!$snapshot) {
            // If the scheduled job hasn't generated anything yet
            return response()->json([
                'message' => 'Statistics are not available yet. Try again in a few minutes.',
            ], 503);
        }

        // Optionally enforce a consistent envelope
        return response()->json([
            'generated_at' => $snapshot['generated_at'] ?? null,
            'top_queries' => $snapshot['top_queries'] ?? [],
            'movies_in_results' => $snapshot['movies_in_results'] ?? [],
            'characters_in_results' => $snapshot['characters_in_results'] ?? [],
            'most_accessed_movies' => $snapshot['most_accessed_movies'] ?? [],
            'most_accessed_characters' => $snapshot['most_accessed_characters'] ?? [],
            'time_buckets' => $snapshot['time_buckets'] ?? [],
            'average_request_length' => $snapshot['average_request_length'] ?? null,
            'cache_hit_percentage' => $snapshot['cache_hit_percentage'] ?? null,
            'request_timing' => $snapshot['request_timing'] ?? null,
        ]);
    }

    public function page()
    {
        return Inertia::render('stats');
    }
}
