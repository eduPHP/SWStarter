import './styles.scss'
import { Card } from '@/components/Card';
import { Heading } from '@/components/Heading';
import { Separator } from '@/components/Separator';
import { useSearch } from '@/hooks/search';
import { Result, ResultType } from '@/components/Result';

export function Results() {
    const {loading, results, type} = useSearch()

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
                    {results.map((result: ResultType) => <Result type={type} result={result} key={result.id} />)}
                </div>}
            </div>
        </Card>
    )
}
