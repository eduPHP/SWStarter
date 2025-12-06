import './styles.scss'
import React from 'react';

export function Button({ children, disabled = true }: { children: React.ReactNode, disabled?: boolean }) {
    return (
        <button className="btn" disabled={disabled}>{children}</button>
    )
}
