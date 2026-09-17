<script>
(function() {
    let idSeq = 0;
    function fixFormFields(root) {
        if (!root || !root.querySelectorAll) return;
        const elements = root.querySelectorAll('input:not([id]):not([name]), select:not([id]):not([name]), textarea:not([id]):not([name])');
        for (let i = 0; i < elements.length; i++) {
            const el = elements[i];
            if (el.hasAttribute('id') || el.hasAttribute('name')) continue;
            const ref = el.getAttribute('x-ref') || el.getAttribute('aria-label') || el.getAttribute('type') || el.tagName.toLowerCase();
            const clean = ref.toLowerCase().replace(/[^a-z0-9]/g, '_').substring(0, 30);
            const generated = 'fi_' + clean + '_' + (++idSeq);
            el.setAttribute('id', generated);
            el.setAttribute('name', generated);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() { fixFormFields(document); });
    } else {
        fixFormFields(document);
    }

    if (window.MutationObserver) {
        const observer = new MutationObserver(function(mutations) {
            for (let i = 0; i < mutations.length; i++) {
                const added = mutations[i].addedNodes;
                for (let j = 0; j < added.length; j++) {
                    const node = added[j];
                    if (node.nodeType === 1) {
                        if (node.matches && node.matches('input:not([id]):not([name]), select:not([id]):not([name]), textarea:not([id]):not([name])')) {
                            const ref = node.getAttribute('x-ref') || node.getAttribute('aria-label') || node.getAttribute('type') || node.tagName.toLowerCase();
                            const clean = ref.toLowerCase().replace(/[^a-z0-9]/g, '_').substring(0, 30);
                            const generated = 'fi_' + clean + '_' + (++idSeq);
                            node.setAttribute('id', generated);
                            node.setAttribute('name', generated);
                        }
                        fixFormFields(node);
                    }
                }
            }
        });
        observer.observe(document.documentElement, { childList: true, subtree: true });
    }
})();
</script>
