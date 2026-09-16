@props(['post'])

@php
    $locale = app()->getLocale();
    $t = $post->translate($locale) ?? $post->translations->first();
    $url = url()->current();
    $title = urlencode($t?->title ?? 'Blog');
    $encodedUrl = urlencode($url);
@endphp

<div class="blog-share-bar" aria-label="{{ __('blog.share_article') }}">
    <span class="mr-2 text-xs font-semibold uppercase tracking-wider text-muted hidden sm:inline-block">
        {{ __('blog.share') }}:
    </span>

    {{-- WhatsApp --}}
    <a
        href="https://api.whatsapp.com/send?text={{ $title }}%20{{ $encodedUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="blog-share-btn blog-share-btn-whatsapp"
        title="WhatsApp"
        aria-label="WhatsApp"
    >
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.159.57 4.184 1.564 5.939l-1.564 5.717 5.864-1.538c1.701.936 3.659 1.473 5.736 1.473 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
        </svg>
    </a>

    {{-- Facebook --}}
    <a
        href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="blog-share-btn blog-share-btn-facebook"
        title="Facebook"
        aria-label="Facebook"
    >
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 8H6v4h3v12h5V12h3.642L18 8h-4V6.333C14 5.374 14.5 5 15.667 5H18V0h-3.889C10.5 0 9 1.5 9 4.667V8z"/>
        </svg>
    </a>

    {{-- X (Twitter) --}}
    <a
        href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $title }}"
        target="_blank"
        rel="noopener noreferrer"
        class="blog-share-btn blog-share-btn-x"
        title="X / Twitter"
        aria-label="X / Twitter"
    >
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
        </svg>
    </a>

    {{-- LinkedIn --}}
    <a
        href="https://www.linkedin.com/sharing/share-offsite/?url={{ $encodedUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="blog-share-btn blog-share-btn-linkedin"
        title="LinkedIn"
        aria-label="LinkedIn"
    >
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
        </svg>
    </a>

    {{-- Copy Link --}}
    <button
        type="button"
        data-copy-url="{{ $url }}"
        data-copied-text="{{ __('blog.copied_to_clipboard') }}"
        class="blog-share-btn blog-share-btn-copy"
        title="Salin Tautan"
        aria-label="Salin Tautan"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
    </button>
</div>
