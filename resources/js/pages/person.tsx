import '../../css/result.scss'
import { Heading } from '@/components/Heading';
import { Separator } from '@/components/Separator';
import { Button } from '@/components/Button';
import { Head, Link, router } from '@inertiajs/react';
import { Card } from '@/components/Card';
import { WhenVisible } from '@inertiajs/react';
import { RelationPlaceholder } from '@/components/RelationPlaceholder';

type PersonType = {
    id: number;
    name: string;
    details: {label: string, value: string}[];
};

type MovieType = {
    id: number;
    title: string;
}

export default function Person({ person, movies }: {person: PersonType, movies?: MovieType[]}) {
    const { name, details } = person;

    return (
        <>
            <Head title="Search" />
            <nav className="navbar">SWStarter</nav>
            <div className="container">
                <Card className="result-details">
                    <Heading title={name} />

                    <div className="result-details-info">
                        <div>
                            <Heading level={2} title="Details" />
                            <Separator />
                            <div>
                                {details.map((detail: { label: string; value: string }) => (
                                    <span className="person-details" key={detail.label}>
                                        {detail.label}: {detail.value}
                                    </span>
                                ))}
                            </div>
                        </div>
                        <div>
                            <Heading level={2} title="Movies" />
                            <Separator />
                            <div className="result-details-movies">
                                <WhenVisible fallback={<RelationPlaceholder />} data="movies" always>
                                    <div>
                                        {movies &&
                                            movies.map((movie: { title: string; id: number }) => (
                                                <Link href={`/movies/${movie.id}`} key={movie.id}>
                                                    {movie.title}
                                                </Link>
                                            ))}
                                    </div>
                                </WhenVisible>
                            </div>
                        </div>
                    </div>

                    <Button onClick={() => router.visit('/')}>Back to search</Button>
                </Card>
            </div>
        </>
    );
}
