const cssVar = (name) => getComputedStyle(document.documentElement).getPropertyValue(name).trim()

export function useTheme() {
    function color(name) {
        return cssVar(`--${name}`)
    }

    function allColors() {
        const vars = [
            'bg', 'bg-alt', 'surface', 'surface-alt',
            'primary', 'primary-hover', 'primary-light',
            'accent', 'accent-hover',
            'text', 'text-muted', 'text-dim',
            'border', 'border-light',
            'success', 'warning', 'danger', 'info',
        ]
        return Object.fromEntries(vars.map((k) => [k, cssVar(k)]))
    }

    return { color, allColors }
}
