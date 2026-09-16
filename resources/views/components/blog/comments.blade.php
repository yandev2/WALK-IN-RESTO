@props(['post', 'comments'])

@php
    $locale = app()->getLocale();
    $slug = $post->translate($locale)?->slug ?? $post->translations->first()?->slug;
    $replyParentId = session('reply_parent_id') ?? old('parent_id');
    $hasReplyErrors = $errors->any() && filled($replyParentId);
@endphp

<section class="mt-14 border-t border-border-subtle pt-10" id="comments">
    <div class="flex items-center justify-between">
        <h2 class="font-display text-2xl font-bold text-body">
            {{ __('blog.comments_title') }}
            @if ($post->comments_count)
                <span class="text-sm font-normal text-muted">({{ number_format($post->comments_count) }})</span>
            @endif
        </h2>
    </div>

    @if ($comments && $comments->isNotEmpty())
        <ul class="mt-6 space-y-5">
            @foreach ($comments as $comment)
                <li id="comment-{{ $comment->id }}" class="blog-card !p-5 scroll-mt-28">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-2.5">
                            <span class="h-8 w-8 rounded-full bg-primary/20 text-primary font-bold flex items-center justify-center text-xs">
                                {{ substr($comment->author_name, 0, 1) }}
                            </span>
                            <div>
                                <p class="font-semibold text-sm text-body">{{ $comment->author_name }}</p>
                                <time class="text-xs text-muted" datetime="{{ $comment->created_at->toIso8601String() }}">
                                    {{ $comment->created_at->translatedFormat('d M Y H:i') }}
                                </time>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="text-xs font-semibold text-primary hover:underline"
                            data-reply-toggle
                            data-parent-id="{{ $comment->id }}"
                            data-parent-name="{{ $comment->author_name }}"
                        >
                            {{ __('blog.reply') }}
                        </button>
                    </div>

                    <p class="mt-3 text-sm leading-relaxed text-body/90 pl-10">{{ $comment->content }}</p>

                    {{-- Replies --}}
                    @if ($comment->relationLoaded('replies') && $comment->replies->isNotEmpty())
                        <ul class="mt-4 space-y-3.5 border-l-2 border-primary/20 pl-4 sm:pl-6 ml-6">
                            @foreach ($comment->replies as $reply)
                                <li class="pt-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-semibold text-sm text-body">{{ $reply->author_name }}</span>
                                        @if ($reply->is_author_reply)
                                            <span class="blog-comment-author-badge">{{ __('blog.author_badge') }}</span>
                                        @endif
                                        <time class="text-xs text-muted" datetime="{{ $reply->created_at->toIso8601String() }}">
                                            • {{ $reply->created_at->translatedFormat('d M Y H:i') }}
                                        </time>
                                    </div>
                                    <p class="mt-1.5 text-sm text-body/80 leading-relaxed">{{ $reply->content }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p class="mt-4 text-sm text-muted">{{ __('blog.no_comments') }}</p>
    @endif

    {{-- Reply Form Panel --}}
    <div
        id="blog-comment-reply-panel"
        class="blog-comment-reply-form mt-8 blog-card !p-6 {{ $hasReplyErrors ? '' : 'hidden' }}"
        data-reply-label="{{ __('blog.reply') }}"
        data-reply-to-template="{{ __('blog.reply_to', ['name' => ':name']) }}"
        @if ($hasReplyErrors) data-reply-open="1" @endif
    >
        <form method="POST" action="{{ route('blog.comments.store', ['slug' => $slug]) }}">
            @csrf
            <input type="hidden" name="parent_id" id="reply-parent-id" value="{{ $replyParentId }}">

            <h3 id="reply-form-title" class="font-display text-lg font-bold text-body">
                {{ __('blog.reply') }}
            </h3>

            @if (session('comment_success') && $replyParentId)
                <p class="mt-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-500">
                    {{ session('comment_success') }}
                </p>
            @endif

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="reply-author_name" class="mb-1 block text-xs font-semibold text-muted">{{ __('blog.comment_name') }} <span class="text-red-500">*</span></label>
                    <input
                        id="reply-author_name"
                        name="author_name"
                        type="text"
                        value="{{ old('author_name') }}"
                        required
                        class="w-full rounded-xl border border-border-subtle bg-surface-base px-3.5 py-2.5 text-sm text-body outline-none focus:border-primary"
                    >
                    @if ($hasReplyErrors)
                        @error('author_name')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    @endif
                </div>
                <div>
                    <label for="reply-author_email" class="mb-1 block text-xs font-semibold text-muted">{{ __('blog.comment_email') }}</label>
                    <input
                        id="reply-author_email"
                        name="author_email"
                        type="email"
                        value="{{ old('author_email') }}"
                        class="w-full rounded-xl border border-border-subtle bg-surface-base px-3.5 py-2.5 text-sm text-body outline-none focus:border-primary"
                    >
                    @if ($hasReplyErrors)
                        @error('author_email')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <label for="reply-content" class="mb-1 block text-xs font-semibold text-muted">{{ __('blog.comment_message') }} <span class="text-red-500">*</span></label>
                <textarea
                    id="reply-content"
                    name="content"
                    rows="3"
                    required
                    class="w-full rounded-xl border border-border-subtle bg-surface-base px-3.5 py-2.5 text-sm text-body outline-none focus:border-primary"
                >{{ old('content') }}</textarea>
                @if ($hasReplyErrors)
                    @error('content')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                    @error('parent_id')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <button type="submit" class="rounded-full bg-primary px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-primary-dark transition">
                    {{ __('blog.submit_reply') }}
                </button>
                <button type="button" class="text-xs font-semibold text-muted hover:text-body transition" data-reply-cancel>
                    {{ __('blog.cancel_reply') }}
                </button>
            </div>
            <p class="mt-2 text-xs text-muted">{{ __('blog.comment_moderation') }}</p>
        </form>
    </div>

    {{-- Main Comment Form --}}
    <form method="POST" action="{{ route('blog.comments.store', ['slug' => $slug]) }}" class="mt-8 blog-card !p-6">
        @csrf
        <h3 class="font-display text-lg font-bold text-body">{{ __('blog.leave_comment') }}</h3>

        @if (session('comment_success') && ! $replyParentId)
            <p class="mt-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-500">
                {{ session('comment_success') }}
            </p>
        @endif

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
                <label for="author_name" class="mb-1 block text-xs font-semibold text-muted">{{ __('blog.comment_name') }} <span class="text-red-500">*</span></label>
                <input
                    id="author_name"
                    name="author_name"
                    type="text"
                    value="{{ $hasReplyErrors ? '' : old('author_name') }}"
                    required
                    class="w-full rounded-xl border border-border-subtle bg-surface-base px-3.5 py-2.5 text-sm text-body outline-none focus:border-primary"
                >
                @if (! $hasReplyErrors)
                    @error('author_name')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                @endif
            </div>
            <div>
                <label for="author_email" class="mb-1 block text-xs font-semibold text-muted">{{ __('blog.comment_email') }}</label>
                <input
                    id="author_email"
                    name="author_email"
                    type="email"
                    value="{{ $hasReplyErrors ? '' : old('author_email') }}"
                    class="w-full rounded-xl border border-border-subtle bg-surface-base px-3.5 py-2.5 text-sm text-body outline-none focus:border-primary"
                >
                @if (! $hasReplyErrors)
                    @error('author_email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                @endif
            </div>
        </div>

        <div class="mt-4">
            <label for="content" class="mb-1 block text-xs font-semibold text-muted">{{ __('blog.comment_message') }} <span class="text-red-500">*</span></label>
            <textarea
                id="content"
                name="content"
                rows="4"
                required
                class="w-full rounded-xl border border-border-subtle bg-surface-base px-3.5 py-2.5 text-sm text-body outline-none focus:border-primary"
            >{{ $hasReplyErrors ? '' : old('content') }}</textarea>
            @if (! $hasReplyErrors)
                @error('content')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            @endif
        </div>

        <div class="mt-4 flex items-center justify-between">
            <button type="submit" class="rounded-full bg-primary px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-primary-dark transition">
                {{ __('blog.submit_comment') }}
            </button>
            <p class="text-xs text-muted hidden sm:block">{{ __('blog.comment_moderation') }}</p>
        </div>
    </form>
</section>
