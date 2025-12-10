<?php

namespace App\Listeners;

use App\Events\StartComputeBucket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class HandleComputeStats implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(StartComputeBucket $event): void
    {
        $snapshot = [
            'generated_at' => $event->time->toIso8601String(),

            'top_queries' => $this->topN('stats:queries', 5),

            'movies_in_results' => $this->topN('stats:movies_in_results', 5),
            'characters_in_results' => $this->topN('stats:characters_in_results', 5),

            'most_accessed_movies' => $this->topN('stats:movies_hits', 5),
            'most_accessed_characters' => $this->topN('stats:characters_hits', 5),

            'time_buckets' => $this->topN('stats:searches_by_bucket', 5),

            'average_request_length' => $this->averageRequestLength(),
            'cache_hit_percentage' => $this->cacheHitPercentage(),
            'request_timing'          => $this->requestTimingStats(),
        ];

        cache()->put('stats:snapshot:latest', $snapshot, now()->addMinutes(10));
    }

    protected function topN(string $cacheKey, int $limit): array
    {
        $data = cache()->get($cacheKey, []); // ['item' => count]
        arsort($data); // highest first

        return array_slice($data, 0, $limit, true);
    }

    protected function averageRequestLength(): ?float
    {
        $sum   = (int) cache()->get('stats:swapi:req_length_sum', 0);
        $count = (int) cache()->get('stats:swapi:req_count', 0);

        if ($count === 0) {
            return 0;
        }

        return $sum / $count;
    }

    protected function cacheHitPercentage(): ?float
    {
        $hits   = (int) cache()->get('stats:swapi:cache_hits', 0);
        $total  = (int) cache()->get('stats:swapi:total_requests', 0);

        if ($total === 0) {
            return null;
        }

        return ($hits / $total) * 100;
    }

    protected function requestTimingStats(): ?array
    {
        $sum   = (int) cache()->get('stats:swapi:time_sum', 0);
        $count = (int) cache()->get('stats:swapi:time_count', 0);

        if ($count === 0) {
            return null;
        }

        $min = cache()->get('stats:swapi:time_min');
        $max = cache()->get('stats:swapi:time_max');

        return [
            'avg_ms' => $sum / $count,
            'min_ms' => $min !== null ? (int) $min : null,
            'max_ms' => $max !== null ? (int) $max : null,
            'count'  => $count,
        ];
    }
}
