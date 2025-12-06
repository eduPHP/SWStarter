import { Head } from '@inertiajs/react';
import { SearchBox } from '@/components/SearchBox';
import { Results } from '@/components/Results';

export default function Search() {
    return (
        <>
            <Head title="Search">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=montserrat:400,500,700" rel="stylesheet" />
            </Head>
            <nav className="navbar">
                SWStarter
            </nav>
            <div className="container">
                <div className="search-container">
                    <aside>
                        <SearchBox />
                    </aside>
                    <main>
                        <Results />
                    </main>
                </div>
            </div>
        </>
    );
}
