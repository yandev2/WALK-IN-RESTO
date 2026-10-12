import fs from 'fs';
const html = fs.readFileSync('scratch_rendered.html', 'utf8');

// Find all tags with inline styles or widths
const classMatches = html.match(/class="[^"]*"/g) || [];
const found = new Set();
for (const c of classMatches) {
    if (c.includes('min-w') || c.includes('w-[') || c.includes('max-w-[') || c.includes('grid-cols')) {
        found.add(c);
    }
}
console.log('Found classes with fixed/min widths or grids:');
found.forEach(item => console.log(item));
