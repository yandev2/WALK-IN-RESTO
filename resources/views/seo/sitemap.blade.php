{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach ($urls as $item)
    <url>
        <loc>{{ $item['url'] }}</loc>
@if (!empty($item['lastmod']))
        <lastmod>{{ $item['lastmod'] }}</lastmod>
@endif
@if (!empty($item['changefreq']))
        <changefreq>{{ $item['changefreq'] }}</changefreq>
@endif
@if (!empty($item['priority']))
        <priority>{{ $item['priority'] }}</priority>
@endif
@if (!empty($item['alternates']))
@foreach ($item['alternates'] as $alt)
        <xhtml:link rel="alternate" hreflang="{{ $alt['hreflang'] }}" href="{{ $alt['href'] }}" />
@endforeach
@endif
@if (!empty($item['image']))
        <image:image>
            <image:loc>{{ $item['image']['url'] }}</image:loc>
@if (!empty($item['image']['title']))
            <image:title>{{ $item['image']['title'] }}</image:title>
@endif
@if (!empty($item['image']['caption']))
            <image:caption>{{ $item['image']['caption'] }}</image:caption>
@endif
        </image:image>
@endif
    </url>
@endforeach
</urlset>
