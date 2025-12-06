import './styles.scss'
import { Card } from '@/components/Card';
import { Heading } from '@/components/Heading';
import { Separator } from '@/components/Separator';
import { useSearch } from '@/hooks/search';

export function Results() {
    const {loading} = useSearch()

    return (
        <Card className="results">
            <Heading title="Results" />
            <Separator />

            <div className="content">
                {loading && <p className="results-message">Searching...</p>}
                {!loading && <p className="results-message">
                    There are zero matches. <br/>
                    Use the form to search for People or Movies.
                </p>}
            </div>
        </Card>
    )
}
