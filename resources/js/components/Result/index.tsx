import { Heading } from '@/components/Heading';
import { Button } from '@/components/Button';
import { router } from '@inertiajs/react';

export type ResultType = {
    id: number;
    title: string;
};

export function Result({ type, result }: { type: string, result: ResultType }) {
    return (
        <div className="result">
            <Heading level={2} title={result.title} />
            <Button onClick={() => router.visit(`/${type}/${result.id}`)}>See Details</Button>
        </div>
    )
}
