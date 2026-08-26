@if ($includeVite)
    @vite(['resources/css/auth-glass.css'])
@endif
@if (filled($cssVariables))
    <style>:root { {!! $cssVariables !!} }</style>
@endif
