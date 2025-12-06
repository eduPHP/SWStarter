import React, {
    createContext,
    useContext,
    useState,
    ReactNode,
} from "react";

type SearchType = "people" | "movies";

type SearchContextValue = {
    query: string;
    type: SearchType;
    results: any[];
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
    const [results, setResults] = useState<any[]>([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    async function search(overrideQuery?: string) {
        const q = overrideQuery ?? query;
        if (!q.trim()) return;

        try {
            setLoading(true);
            setError(null);

            // TODO: API call

            // Dummy data for now:
            await new Promise((r) => setTimeout(r, 400));
            setResults([
                { id: 1, name: `Result for "${q}" (${type})` },
                { id: 2, name: `Another match for "${q}" (${type})` },
            ]);
        } catch (e: any) {
            setError(e?.message ?? "Unknown error");
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
