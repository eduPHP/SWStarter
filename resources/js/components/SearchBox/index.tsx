import './styles.scss'
import { Card } from '@/components/Card';
import { Input } from '@/components/Input';
import { Button } from '@/components/Button';
import { useState } from 'react';
import { RadioGroup } from '@/components/RadioGroup';
import { useSearch } from '@/hooks/search';

export function SearchBox() {
    const [query, setQuery] = useState('');
    const [selectedOption, setSelectedOption] = useState('people');

    const {search, loading} = useSearch()

    return (
        <Card className="search">
            <p>What are you searching for?</p>

            <RadioGroup value={selectedOption} options={[
                { label: 'People', key: 'people' },
                { label: 'Movies', key: 'movies' }
            ]} onSelect={setSelectedOption}/>

            <Input onChange={(ev) => setQuery(ev.target.value)} type="text" placeholder="e.g. Chewbacca, Yoda, Boba Fett"/>
            <Button onClick={() => search(query)} disabled={query.length === 0}>
                {loading ? 'Searching...' : 'Search'}
            </Button>
        </Card>
    )
}
