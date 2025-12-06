import React, { JSX } from "react";

type HeadingLevel = 1 | 2; // only what we use for now

type HeadingProps = {
    level?: HeadingLevel | `${HeadingLevel}`;
    title?: string;
    className?: string;
};

export function Heading({level = 1, title, className = ""}: HeadingProps) {
    const numericLevel = typeof level === "string" ? parseInt(level, 10) : level;

    const Tag = `h${numericLevel}` as keyof JSX.IntrinsicElements;

    return (
        <Tag className={`heading level-${numericLevel} ${className}`}>
            {title}
        </Tag>
    );
}
