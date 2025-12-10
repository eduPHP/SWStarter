<?php

namespace SWApi\Services;

class StatsRecorder
{
    public function recordMovieSearch(string $query, array $movieIds): void
    {
        $timestamp = now();

        // Top queries
        $this->incrementArrayCounter('stats:queries', $query);

        // Movies that appear most in results
        foreach ($movieIds as $id) {
            $this->incrementArrayCounter('stats:movies_in_results', $id);
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
        // Round down to nearest 5 minutes for grouping
        $minute = (int) floor($time->minute / 5) * 5;

        return $time->copy()->minute($minute)->second(0)->format('Y-m-d H:i');
    }

    public function recordCharacterSearch(string $query, array $characterIds): void
    {
        $timestamp = now();

        // Top queries
        $this->incrementArrayCounter('stats:queries', $query);

        // Characters that appear most in results
        foreach ($characterIds as $id) {
            $this->incrementArrayCounter('stats:characters_in_results', $id);
        }

        // Time buckets (per 5-minute block)
        $bucket = $this->bucketKey($timestamp);
        $this->incrementArrayCounter('stats:searches_by_bucket', $bucket);
    }
}
