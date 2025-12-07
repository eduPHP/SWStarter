import { SearchType } from '@/hooks/search';

type RadioGroupOptions = {
    options: {
        label: string
        key: string
    }[];
    onSelect: (t: SearchType) => void
    value: string
};

export function TypeSelector({ options, onSelect, value }: RadioGroupOptions) {
    return (
        <div className="radio-group">
            {options.map(({ label, key }) => (
                <label key={key}>
                    <input checked={key === value} onChange={() => onSelect(key as SearchType)} type="radio" name="search" value={value} />
                    {label}
                </label>
            ))}
        </div>
    );
}
