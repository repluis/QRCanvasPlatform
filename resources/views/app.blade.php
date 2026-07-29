<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <style>
            :root {
                --bg: {{ config('palette.colors.bg') }};
                --bg-alt: {{ config('palette.colors.bg-alt') }};
                --surface: {{ config('palette.colors.surface') }};
                --surface-alt: {{ config('palette.colors.surface-alt') }};
                --primary: {{ config('palette.colors.primary') }};
                --primary-hover: {{ config('palette.colors.primary-hover') }};
                --primary-light: {{ config('palette.colors.primary-light') }};
                --accent: {{ config('palette.colors.accent') }};
                --accent-hover: {{ config('palette.colors.accent-hover') }};
                --text: {{ config('palette.colors.text') }};
                --text-muted: {{ config('palette.colors.text-muted') }};
                --text-dim: {{ config('palette.colors.text-dim') }};
                --border: {{ config('palette.colors.border') }};
                --border-light: {{ config('palette.colors.border-light') }};
                --success: {{ config('palette.colors.success') }};
                --warning: {{ config('palette.colors.warning') }};
                --danger: {{ config('palette.colors.danger') }};
                --info: {{ config('palette.colors.info') }};
                --font-family: {{ config('palette.fonts.family') }};
                --font-mono: {{ config('palette.fonts.mono') }};
                --radius-sm: {{ config('palette.radius.sm') }};
                --radius-md: {{ config('palette.radius.md') }};
                --radius-lg: {{ config('palette.radius.lg') }};
                --radius-xl: {{ config('palette.radius.xl') }};
            }
            body {
                font-family: var(--font-family);
                background-color: var(--bg);
                color: var(--text);
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
