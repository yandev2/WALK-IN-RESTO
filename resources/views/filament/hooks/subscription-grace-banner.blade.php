@if (! empty($message))
    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        {{ $message }}
        @if (! empty($url))
            <a href="{{ $url }}" class="ml-2 font-semibold underline">Lihat langganan</a>
        @endif
    </div>
@endif
