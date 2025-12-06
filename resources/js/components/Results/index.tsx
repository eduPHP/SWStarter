import './styles.scss'
import { Card } from '@/components/Card';
import { Heading } from '@/components/Heading';
import { Separator } from '@/components/Separator';
import { Result, useSearch } from '@/hooks/search';
import { Button } from '@/components/Button';

export function Results() {
    const {loading, results} = useSearch()

    return (
        <Card className="results">
            <Heading title="Results" />
            <Separator />

            <div className="content">
                {loading && <p className="results-message">Searching...</p>}
                {!loading && !results.length && <p className="results-message">
                    There are zero matches. <br/>
                    Use the form to search for People or Movies.
                </p>}
                {!loading && results.length > 0 && <div className="results-list">
                    {results.map((result: Result) => <div className="result" key={result.id}>
                        <Heading level={2} title={result.title} />
                        <Button onClick={() => null}>See Details</Button>
                    </div>)}
                </div>}
            </div>
        </Card>
    )
}
