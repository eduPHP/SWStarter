import './styles.scss'
import React from 'react';

export function Button({ children, onClick, disabled = false }: { children: React.ReactNode, disabled?: boolean, onClick: () => void }) {
    return (
        <button onClick={onClick} className="btn" disabled={disabled}>{children}</button>
    )
}
