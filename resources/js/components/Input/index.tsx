import './styles.scss'
import React, { ChangeEvent } from 'react';

export function Input({ value, type, placeholder, onChange, onEnter }: { value: string, type: string, placeholder?: string, onEnter?: () => void, onChange?: (ev: ChangeEvent<HTMLInputElement>) => void}) {
    const handleEnter = (ev: React.KeyboardEvent<HTMLInputElement>) => {
        if (ev.key === 'Enter' && onEnter) onEnter();
    }

    return <input onKeyUp={handleEnter} value={value} onChange={onChange} type={type} placeholder={placeholder} className="input-field" />;
}
