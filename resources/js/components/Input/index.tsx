import './styles.scss'
import { ChangeEvent } from 'react';

export function Input({ type, placeholder, onChange }: { type: string, placeholder?: string, onChange?: (ev: ChangeEvent<HTMLInputElement>) => void}) {
    return (
        <input onChange={onChange} type={type} placeholder={placeholder} className="input-field" />
    )
}
