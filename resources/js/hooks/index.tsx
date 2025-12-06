import { SearchProvider } from '@/hooks/search';

export function Contexts({children}: { children: React.ReactNode }) {
    return (
        <SearchProvider>
            {children}
        </SearchProvider>
    )
}
