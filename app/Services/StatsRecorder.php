<?php

namespace App\Services;

class StatsRecorder
{
    public function recordMovieSearch(string $query, array $movieTitles): void
    {
        $timestamp = now();

        // Top queries
        $this->incrementArrayCounter('stats:queries', $query);

        // Movies that appear most in results
        foreach ($movieTitles as $title) {
            $this->incrementArrayCounter('stats:movies_in_results', $title);
        }

        // Time buckets (per 5-minute block)
        $bucket = $this->bucketKey($timestamp);
        $this->incrementArrayCounter('stats:searches_by_bucket', $bucket);
    }

    public function recordMovieDetails(int|string $movieId): void
    {
        $this->incrementArrayCounter('stats:movies_hits', $movieId);
    }

    public function recordCharacterDetails(int|string $characterId): void
    {
        $this->incrementArrayCounter('stats:characters_hits', $characterId);
    }

    protected function incrementArrayCounter(string $cacheKey, string|int $itemKey, int $by = 1): void
    {
        $data = cache()->get($cacheKey, []);

        $itemKey = (string) $itemKey;
        $data[$itemKey] = ($data[$itemKey] ?? 0) + $by;

        cache()->put($cacheKey, $data, now()->addDay());
    }

    protected function bucketKey(\Carbon\CarbonInterface $time): string
    {
        // Round down to the nearest 5 minutes for grouping
        $minute = (int) floor($time->minute / 5) * 5;

        return $time->copy()->minute($minute)->second(0)->format('Y-m-d H:i');
    }

    public function recordCharacterSearch(string $query, array $characterNames): void
    {
        $timestamp = now();

        // Top queries
        $this->incrementArrayCounter('stats:queries', $query);

        // Characters that appear most in results
        foreach ($characterNames as $name) {
            $this->incrementArrayCounter('stats:characters_in_results', $name);
        }

        // Time buckets (per 5-minute block)
        $bucket = $this->bucketKey($timestamp);
        $this->incrementArrayCounter('stats:searches_by_bucket', $bucket);
    }

    public function recordCacheStats(string $path, array $query, bool $hitFromCache, int $durationMs): void
    {
        $length = strlen($path . json_encode($query));

        $this->statIncrement('stats:swapi:total_requests');

        if ($hitFromCache) {
            $this->statIncrement('stats:swapi:cache_hits');
        } else {
            $this->statIncrement('stats:swapi:cache_misses');
        }

        $this->statIncrement('stats:swapi:req_length_sum', $length);
        $this->statIncrement('stats:swapi:req_count', 1);

        $this->recordTiming($durationMs);
    }

    protected function recordTiming(int $durationMs): void
    {
        // sum + count (explicit increments)
        $this->statIncrement('stats:swapi:time_sum', $durationMs);
        $this->statIncrement('stats:swapi:time_count', 1);

        // min
        $currentMin = cache()->get('stats:swapi:time_min');
        if ($currentMin === null || $durationMs < (int) $currentMin) {
            cache()->forever('stats:swapi:time_min', $durationMs);
        }

        // max
        $currentMax = cache()->get('stats:swapi:time_max');
        if ($currentMax === null || $durationMs > (int) $currentMax) {
            cache()->forever('stats:swapi:time_max', $durationMs);
        }
    }

    protected function statIncrement(string $key, int $by = 1): void
    {
        $current = cache()->get($key, 0);
        $new     = (int) $current + $by;

        cache()->forever($key, $new);
    }
}
