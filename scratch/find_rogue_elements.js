import fs from 'fs';
const html = fs.readFileSync('scratch_rendered.html', 'utf8');

// Look for elements that might have large min-width or content:
// 1. Check for long unbroken strings (e.g. URLs, data urls, long words without spaces)
const longWords = html.match(/[^\s<>"'\/]{30,}/g) || [];
console.log('Long unbroken words (>30 chars):', longWords.length);
longWords.filter(w => !w.startsWith('http') && !w.startsWith('data:') && !w.startsWith('hash:') && !w.includes('token') && !w.includes('sha')).slice(0, 10).forEach(w => console.log('Word:', w));

// 2. Look for table or flex-nowrap or inline SVGs
const svgs = html.match(/<svg[^>]*>/gi) || [];
console.log('\nSVGs count:', svgs.length);
svgs.forEach(s => {
    if (s.includes('width') || s.includes('w-')) console.log('SVG:', s);
});

// 3. Look for elements with style="..."
const styles = html.match(/style="[^"]*"/gi) || [];
console.log('\nInline styles count:', styles.length);
styles.forEach(s => console.log('Style:', s));
