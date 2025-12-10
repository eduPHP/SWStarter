import '../../css/stats.scss';

import { Head } from '@inertiajs/react';
import { useCallback, useEffect, useState } from 'react';
import { Button } from '@/components/Button';
import { Card } from '@/components/Card';

type StatsMap = Record<string, number>;

interface StatsRequestTiming {
    avg_ms: number;
    min_ms: number;
    max_ms: number;
    count: number;
}

interface StatsResponse {
    generated_at: string | null;
    top_queries: StatsMap;
    movies_in_results: StatsMap;
    characters_in_results: StatsMap;
    most_accessed_movies: StatsMap;
    most_accessed_characters: StatsMap;
    time_buckets: StatsMap;
    average_request_length: number | null;
    cache_hit_percentage: number | null;
    request_timing: StatsRequestTiming | null;
}

const formatDateTime = (value: string | null): string | null => {
    if (!value) {
        return null;
    }

    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return null;
    }

    return new Intl.DateTimeFormat(undefined, {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(date);
};

const formatDecimal = (value: number | null, fractionDigits = 2): string => {
    if (value === null || value === undefined) {
        return '--';
    }

    return value.toLocaleString(undefined, {
        minimumFractionDigits: 0,
        maximumFractionDigits: fractionDigits,
    });
};

const formatMs = (value: number | null): string => {
    if (value === null || value === undefined) {
        return '--';
    }

    return `${Math.round(value).toLocaleString()} ms`;
};

const formatPercent = (value: number | null): string => {
    if (value === null || value === undefined) {
        return '--';
    }

    return `${value.toFixed(2)}%`;
};

const formatRequests = (value: number): string => {
    const rounded = Math.round(value);
    return `${rounded.toLocaleString()} request${rounded === 1 ? '' : 's'}`;
};

const normalizeStats = (payload: Partial<StatsResponse> | null | undefined): StatsResponse => {
    const map = (data?: StatsMap | null): StatsMap => ({ ...(data ?? {}) });
    const requestTiming = payload?.request_timing
        ? {
            avg_ms: payload.request_timing.avg_ms ?? 0,
            min_ms: payload.request_timing.min_ms ?? 0,
            max_ms: payload.request_timing.max_ms ?? 0,
            count: payload.request_timing.count ?? 0,
        }
        : null;

    return {
        generated_at: payload?.generated_at ?? null,
        top_queries: map(payload?.top_queries),
        movies_in_results: map(payload?.movies_in_results),
        characters_in_results: map(payload?.characters_in_results),
        most_accessed_movies: map(payload?.most_accessed_movies),
        most_accessed_characters: map(payload?.most_accessed_characters),
        time_buckets: map(payload?.time_buckets),
        average_request_length: payload?.average_request_length ?? null,
        cache_hit_percentage: payload?.cache_hit_percentage ?? null,
        request_timing: requestTiming,
    };
};

export default function Stats() {
    const [stats, setStats] = useState<StatsResponse | null>(null);
    const [error, setError] = useState<string | null>(null);
    const [isLoading, setIsLoading] = useState(true);

    const loadStats = useCallback(async (signal?: AbortSignal) => {
        setIsLoading(true);
        setError(null);

        try {
            const response = await fetch('/api/stats', { signal });
            let payload: Partial<StatsResponse> | null = null;

            try {
                payload = await response.json();
            } catch {
                payload = null;
            }

            if (!response.ok) {
                const message = typeof payload === 'object' && payload && 'message' in payload
                    ? String((payload as { message?: unknown }).message ?? 'Unable to load stats')
                    : 'Unable to load stats';

                throw new Error(message);
            }

            setStats(normalizeStats(payload));
        } catch (fetchError) {
            if (signal?.aborted) {
                return;
            }
            const message = fetchError instanceof Error ? fetchError.message : 'Unable to load stats';
            setError(message);
        } finally {
            if (!signal?.aborted) {
                setIsLoading(false);
            }
        }
    }, []);

    useEffect(() => {
        const controller = new AbortController();
        loadStats(controller.signal);

        return () => controller.abort();
    }, [loadStats]);

    const renderStatList = (data: StatsMap, emptyCopy: string, valueFormatter?: (value: number) => string, sortBy: 'value' | 'key' = 'value') => {
        const entries = Object.entries(data ?? {});

        if (sortBy === 'value') {
            entries.sort((a, b) => b[1] - a[1]);
        } else {
            entries.sort((a, b) => a[0].localeCompare(b[0]));
        }

        if (!entries.length) {
            return <p className="stat-empty">{emptyCopy}</p>;
        }

        return (
            <dl className="stat-list">
                {entries.map(([label, value]) => (
                    <div className="stat-row" key={label}>
                        <dt>{label}</dt>
                        <dd>{valueFormatter ? valueFormatter(value) : value.toLocaleString()}</dd>
                    </div>
                ))}
            </dl>
        );
    };

    const formattedGeneratedAt = stats ? formatDateTime(stats.generated_at) : null;
    const snapshotLabel = stats
        ? (formattedGeneratedAt ? `Snapshot captured ${formattedGeneratedAt}` : 'Waiting for the first snapshot...')
        : 'Snapshot captured just now';

    const sections = stats ? [
        {
            title: 'Top Queries',
            data: stats.top_queries,
            emptyCopy: 'No queries have been recorded yet.',
        },
        {
            title: 'Movies In Results',
            data: stats.movies_in_results,
            emptyCopy: 'No movies have been returned in search results yet.',
        },
        {
            title: 'Characters In Results',
            data: stats.characters_in_results,
            emptyCopy: 'No characters have been returned in search results yet.',
        },
        {
            title: 'Most Accessed Movies',
            data: stats.most_accessed_movies,
            emptyCopy: 'Movie detail pages have not been visited yet.',
        },
        {
            title: 'Most Accessed Characters',
            data: stats.most_accessed_characters,
            emptyCopy: 'Character detail pages have not been visited yet.',
        },
        {
            title: 'Recent Traffic Buckets',
            data: stats.time_buckets,
            emptyCopy: 'No requests detected for the tracked time window.',
            sortBy: 'key' as const,
            valueFormatter: formatRequests,
        },
    ] : [];

    return (
        <>
            <Head title="Stats" />
            <nav className="navbar">
                SWStarter
            </nav>
            <div className="container">
                <div className="stats-page">
                    <Card className="stats-hero">
                        <div>
                            <p className="stats-eyebrow">Usage insights</p>
                            <h1>Search activity dashboard</h1>
                            <p className="stats-subtitle">
                                A quick overview of what people are searching for on SWStarter, plus how the API is performing.
                            </p>
                        </div>
                        <div className="stats-hero-meta">
                            <p className="stats-updated">
                                {snapshotLabel}
                            </p>
                            <div className="stats-hero-actions">
                                {isLoading && (
                                    <span className="stats-loading">Refreshing...</span>
                                )}
                                <Button onClick={() => loadStats()} disabled={isLoading}>
                                    Refresh
                                </Button>
                            </div>
                        </div>
                    </Card>

                    {isLoading && !stats && (
                        <Card>
                            <p className="stat-empty">Loading latest stats...</p>
                        </Card>
                    )}

                    {error && (
                        <Card className="stats-error">
                            <div>
                                <h2>We could not load the dashboard.</h2>
                                <p>{error}</p>
                            </div>
                            <Button onClick={() => loadStats()} disabled={isLoading}>
                                Try again
                            </Button>
                        </Card>
                    )}

                    {stats && (
                        <>
                            <section className="stats-summary-grid">
                                <Card className="summary-card">
                                    <p className="summary-label">Average request length</p>
                                    <p className="summary-value">{formatDecimal(stats.average_request_length)}</p>
                                    <p className="summary-hint">characters per query</p>
                                </Card>
                                <Card className="summary-card">
                                    <p className="summary-label">Cache hit rate</p>
                                    <p className="summary-value">{formatPercent(stats.cache_hit_percentage)}</p>
                                    <p className="summary-hint">share of requests served from cache</p>
                                </Card>
                                <Card className="summary-card">
                                    <p className="summary-label">Request timing</p>
                                    {stats.request_timing ? (
                                        <ul className="timing-list">
                                            <li>
                                                <span>Avg</span>
                                                <strong>{formatMs(stats.request_timing.avg_ms)}</strong>
                                            </li>
                                            <li>
                                                <span>Min</span>
                                                <strong>{formatMs(stats.request_timing.min_ms)}</strong>
                                            </li>
                                            <li>
                                                <span>Max</span>
                                                <strong>{formatMs(stats.request_timing.max_ms)}</strong>
                                            </li>
                                            <li>
                                                <span>Samples</span>
                                                <strong>{stats.request_timing.count.toLocaleString()}</strong>
                                            </li>
                                        </ul>
                                    ) : (
                                        <p className="stat-empty">Timing data not available yet.</p>
                                    )}
                                </Card>
                            </section>

                            <section className="stats-grid">
                                {sections.map(({ title, data, emptyCopy, valueFormatter, sortBy }) => (
                                    <Card key={title}>
                                        <div className="stat-card-header">
                                            <h2>{title}</h2>
                                        </div>
                                        {renderStatList(data, emptyCopy, valueFormatter, sortBy)}
                                    </Card>
                                ))}
                            </section>
                        </>
                    )}
                </div>
            </div>
        </>
    );
}
