import '../../css/result.scss'
import { Heading } from '@/components/Heading';
import { Separator } from '@/components/Separator';
import { Button } from '@/components/Button';
import { Head, router } from '@inertiajs/react';
import { Card } from '@/components/Card';

type PersonType = {
    id: number;
    name: string;
    details: {name: string, value: string}[];
    movies: {title: string, id: number}[];
};

export default function person({ person }: {person: PersonType}) {
    const { name, details, movies } = person;

    return (
        <>
            <Head title="Search" />
            <nav className="navbar">
                SWStarter
            </nav>
            <div className="container">
                <Card className="result-details">
                    <Heading title={name} />

                    <div className="result-details-info">
                        <div>
                            <Heading level={2} title="Details" />
                            <Separator />
                            <div>
                                { details.map((detail: {name: string, value: string}) => <p key={detail.name}>{detail.name}: {detail.value}</p>) }
                            </div>
                        </div>
                        <div>
                            <Heading level={2} title="Movies" />
                            <Separator />
                            <div className="result-details-movies">
                                { movies.map((movie: {title: string, id: number}) => <a href={`/movies/${movie.id}`} key={movie.id}>{movie.title}</a>) }
                            </div>
                        </div>
                    </div>

                    <Button onClick={() => router.visit('/')}>Back to search</Button>
                </Card>
            </div>
        </>

    )
}
