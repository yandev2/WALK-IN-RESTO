// Blog Interactions (Adapted from Cetak Biru Blogger / MyPorto)

export function initBlogLike() {
    const buttons = document.querySelectorAll('.blog-like-btn');
    if (!buttons.length) return;

    buttons.forEach((button) => {
        button.addEventListener('click', async () => {
            if (button.dataset.loading === '1') return;

            const url = button.dataset.likeUrl;
            if (!url) return;

            button.dataset.loading = '1';
            button.classList.add('is-loading');

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token || '',
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    const liked = Boolean(data.liked);
                    const count = Number(data.likes_count ?? 0);

                    buttons.forEach((btn) => {
                        if (btn.dataset.likeUrl === url) {
                            btn.dataset.liked = liked ? '1' : '0';
                            btn.classList.toggle('is-liked', liked);
                            btn.setAttribute('aria-pressed', liked ? 'true' : 'false');

                            const countEl = btn.querySelector('.blog-like-count');
                            if (countEl) {
                                countEl.textContent = new Intl.NumberFormat().format(count);
                            }
                        }
                    });
                }
            } catch (err) {
                console.error('Like toggle failed', err);
            } finally {
                buttons.forEach((btn) => {
                    if (btn.dataset.likeUrl === url) {
                        btn.dataset.loading = '0';
                        btn.classList.remove('is-loading');
                    }
                });
            }
        });
    });
}

export function initBlogReplyToggle() {
    const panel = document.getElementById('blog-comment-reply-panel');
    if (!panel) return;

    const parentIdInput = document.getElementById('reply-parent-id');
    const titleEl = document.getElementById('reply-form-title');
    const toggleButtons = document.querySelectorAll('[data-reply-toggle]');
    const cancelButton = panel.querySelector('[data-reply-cancel]');

    const replyToTemplate = panel.dataset.replyToTemplate || 'Balas ke :name';

    const openPanel = (parentId, parentName) => {
        if (parentIdInput) {
            parentIdInput.value = parentId;
        }

        if (titleEl) {
            titleEl.textContent = replyToTemplate.replace(':name', parentName);
        }

        panel.classList.remove('hidden');
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        const contentField = panel.querySelector('#reply-content');
        if (contentField) {
            contentField.focus();
        }
    };

    const closePanel = () => {
        panel.classList.add('hidden');
        if (parentIdInput) {
            parentIdInput.value = '';
        }
        if (titleEl) {
            titleEl.textContent = panel.dataset.replyLabel || 'Balas';
        }
    };

    toggleButtons.forEach((button) => {
        button.addEventListener('click', () => {
            openPanel(button.dataset.parentId, button.dataset.parentName || '');
        });
    });

    if (cancelButton) {
        cancelButton.addEventListener('click', closePanel);
    }
}

export function initBlogShare() {
    const copyButtons = document.querySelectorAll('[data-copy-url]');
    copyButtons.forEach((btn) => {
        btn.addEventListener('click', async () => {
            const url = btn.dataset.copyUrl || window.location.href;
            try {
                await navigator.clipboard.writeText(url);
                const originalTitle = btn.getAttribute('title');
                btn.setAttribute('title', btn.dataset.copiedText || 'Tautan disalin!');
                btn.classList.add('text-primary');

                // Quick visual feedback
                setTimeout(() => {
                    btn.setAttribute('title', originalTitle);
                    btn.classList.remove('text-primary');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy', err);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initBlogLike();
    initBlogReplyToggle();
    initBlogShare();
});
