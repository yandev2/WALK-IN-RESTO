import fs from 'fs';
const html = fs.readFileSync('scratch_rendered.html', 'utf8');

// Let's inspect the entire rendered HTML structure from body down
// Print all tags and their class attribute
const tagRegex = /<([a-z0-9-]+)(\s+[^>]*)?>/gi;
let match;
const tags = [];
while ((match = tagRegex.exec(html)) !== null) {
    const tagName = match[1];
    const attrs = match[2] || '';
    if (attrs.includes('style=') || attrs.includes('w-') || attrs.includes('grid-cols') || attrs.includes('table')) {
        tags.push({ tag: tagName, snippet: match[0].substring(0, 150) });
    }
}

console.log('Total matched tags:', tags.length);
tags.forEach((t, i) => {
    if (t.snippet.includes('style') || t.snippet.includes('min-w') || t.snippet.includes('w-[') || t.snippet.includes('100%') || t.snippet.includes('rem')) {
        console.log(`[${i}] <${t.tag}> ${t.snippet}`);
    }
});
