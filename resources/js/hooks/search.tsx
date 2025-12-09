import React, {
    createContext,
    useContext,
    useState,
    ReactNode,
} from "react";
import { ResultType } from '@/components/Result';

export type SearchType = "people" | "movies";

type SearchContextValue = {
    query: string;
    type: SearchType;
    results: ResultType[];
    loading: boolean;
    error: string | null;
    setQuery: (q: string) => void;
    setType: (t: SearchType) => void;
    search: (q?: string) => Promise<void>;
};

const SearchContext = createContext<SearchContextValue | undefined>(
    undefined
);

type SearchProviderProps = {
    children: ReactNode;
};

export function SearchProvider({ children }: SearchProviderProps) {
    const [query, setQuery] = useState("");
    const [type, setType] = useState<SearchType>("people");
    const [results, setResults] = useState<ResultType[]>([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    async function search() {
        const q = query;
        if (!q.trim()) return;

        try {
            setLoading(true);
            setError(null);

            const request = await fetch(`/find/${type}?q=${encodeURIComponent(q)}`)
            const data = await request.json();
            setResults(data)

            setError(null)
        } catch (err) {
            setError("Request error");
            console.log(err)
        } finally {
            setLoading(false);
        }
    }

    const value: SearchContextValue = {
        query,
        type,
        results,
        loading,
        error,
        setQuery,
        setType,
        search,
    };

    return (
        <SearchContext.Provider value={value}>
            {children}
        </SearchContext.Provider>
    );
}

export function useSearch() {
    const ctx = useContext(SearchContext);
    if (!ctx) {
        throw new Error("useSearch must be used inside <SearchProvider />");
    }
    return ctx;
}
