import './styles.scss'
export function RelationPlaceholder() {
    const random = () => {
        const range = 70 - 40;

        return Math.floor(Math.random() * (range + 1)) + 40 + '%';
    }

    return (
        <div className={`movies-placeholder`}>
            <div style={{ width: random() }}></div>
            <div style={{ width: random() }}></div>
            <div style={{ width: random() }}></div>
            <div style={{ width: random() }}></div>
        </div>
    );
}
