import './styles.scss'
export function Heading({title}: { title: string}) {
    return (
        <>
            <h1 className="heading-1">{title}</h1>
        </>
    )
}
