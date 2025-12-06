import { Head } from '@inertiajs/react';
import { SearchBox } from '@/components/SearchBox';
import { Results } from '@/components/Results';

export default function Search() {
    return (
        <>
            <Head title="Search" />
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
