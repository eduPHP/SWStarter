import './styles.scss'
import { Card } from '@/components/Card';
import { Heading } from '@/components/Heading';
import { Separator } from '@/components/Separator';

export function Results() {
    return (
        <Card className="results">
            <Heading title="Results" />
            <Separator />

            <div className="content">
                <p className="no-results">
                    There are zero matches. <br/>
                    Use the form to search for People or Movies.
                </p>
            </div>
        </Card>
    )
}
