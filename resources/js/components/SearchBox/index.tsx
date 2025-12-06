import './styles.scss'
import { Card } from '@/components/Card';
import { Input } from '@/components/Input';
import { Button } from '@/components/Button';
import { useState } from 'react';
import { RadioGroup } from '@/components/RadioGroup';

export function SearchBox() {
    const [search, setSearch] = useState('');
    const [selectedOption, setSelectedOption] = useState('people');

    return (
        <Card className="search">
            <p>What are you searching for?</p>

            <RadioGroup value={selectedOption} options={[
                { label: 'People', key: 'people' },
                { label: 'Movies', key: 'movies' }
            ]} onSelect={setSelectedOption}/>

            <Input onChange={(ev) => setSearch(ev.target.value)} type="text" placeholder="e.g. Chewbacca, Yoda, Boba Fett"/>
            <Button disabled={search.length === 0}>Search</Button>
        </Card>
    )
}
