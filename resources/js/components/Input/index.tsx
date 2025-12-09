import './styles.scss'
import { ChangeEvent } from 'react';

export function Input({ value, type, placeholder, onChange }: { value: string, type: string, placeholder?: string, onChange?: (ev: ChangeEvent<HTMLInputElement>) => void}) {
    return <input value={value} onChange={onChange} type={type} placeholder={placeholder} className="input-field" />;
}
