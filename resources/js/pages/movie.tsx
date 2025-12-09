import '../../css/result.scss'
import { Heading } from '@/components/Heading';
import { Separator } from '@/components/Separator';
import { Button } from '@/components/Button';
import { Head, Link, router } from '@inertiajs/react';
import { Card } from '@/components/Card';

type MovieType = {
    id: number;
    title: string;
    details: string;
    characters: {name: string, id: number}[];
}

export default function Movie({ movie }: {movie: MovieType}) {
    const { title, details, characters } = movie;

    return (
        <>
            <Head title="Search" />
            <nav className="navbar">
                SWStarter
            </nav>

            <div className="container">
                <Card className="result-details">
                    <Heading title={title} />

                    <div className="result-details-info">
                        <div>
                            <Heading level={2} title="Opening Crawl" />
                            <Separator />
                            <div className="result-details-info-description">
                                { details }
                            </div>
                        </div>
                        <div className="result-details-characters">
                            <Heading level={2} title="Characters" />
                            <Separator />
                            <div>
                                { characters.map((character: {name: string, id: number}) => (
                                    <span key={character.id}>
                                        <Link href={`/people/${character.id}`}>{character.name}</Link>
                                    </span>
                                )) }
                            </div>
                        </div>
                    </div>

                    <Button onClick={() => router.visit('/')}>Back to search</Button>
                </Card>
            </div>
        </>
    )
}
