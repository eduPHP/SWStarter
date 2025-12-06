import { Heading } from '@/components/Heading';
import { Button } from '@/components/Button';

export type ResultType = {
    id: number;
    title: string;
};

export function Result({ result }: { result: ResultType }) {
    return (
        <div className="result">
            <Heading level={2} title={result.title} />
            <Button onClick={() => null}>See Details</Button>
        </div>
    )
}
