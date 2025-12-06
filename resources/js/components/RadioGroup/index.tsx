type RadioGroupOptions = {
    options: {
        label: string
        key: string
    }[];
    onSelect: (value: string) => void
    value: string
};

export function RadioGroup({ options, onSelect, value }: RadioGroupOptions) {
    return (
        <div className="radio-group">
            {options.map(({ label, key }) => (
                <label key={key}>
                    <input checked={key === value} onChange={() => onSelect(key)} type="radio" name="search" value={value} />
                    {label}
                </label>
            ))}
        </div>
    );
}
