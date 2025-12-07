import './styles.scss'
import { Card } from '@/components/Card';
import { Input } from '@/components/Input';
import { Button } from '@/components/Button';
import { TypeSelector } from '@/components/TypeSelector';
import { useSearch } from '@/hooks/search';

export function SearchBox() {
    const {search, loading, type, setType, query, setQuery} = useSearch()

    return (
        <Card className="search">
            <p>What are you searching for?</p>

            <TypeSelector value={type} options={[
                { label: 'People', key: 'people' },
                { label: 'Movies', key: 'movies' }
            ]} onSelect={setType}/>

            <Input onChange={(ev) => setQuery(ev.target.value)} type="text" placeholder={type === 'people' ? 'e.g. Chewbacca, Yoda, Boba Fett' : 'e.g. Return of the Jedi, The Clone Wars'}/>
            <Button onClick={() => search(query)} disabled={query.length === 0}>
                {loading ? 'Searching...' : 'Search'}
            </Button>
        </Card>
    )
}
