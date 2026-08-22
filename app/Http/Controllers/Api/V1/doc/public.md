# Publik

Tanpa token. Restoran non-aktif → `404`. Throttle 60/menit.

Controller: `App\Http\Controllers\Api\V1\Public`.

---

## GET `/restaurants`

**Kegunaan:** Daftar restoran aktif untuk discovery (nama, slug, logo, tema, alamat, buka sekarang, rating).

**Auth:** tidak ada

**Query:** tidak ada

**Response `200`:** array `RestaurantSummaryResource`

```json
{
  "data": [
    {
      "slug": "resto-demo",
      "name": "Resto Demo",
      "logo_url": "https://...",
      "theme": { "primary": "#F97316", "primary_dark": "#D66313", "accent": "#FB923C" },
      "headline": "...",
      "address": "Jl. ...",
      "is_open_now": true,
      "rating_average": 4.5,
      "rating_count": 12
    }
  ]
}
```

`rating_average` `null` jika belum ada ulasan. `is_open_now` mengikuti jam operasional + tanggal tutup outlet default, bukan tombol kasir.

---

## GET `/restaurants/{slug}`

**Kegunaan:** Payload landing page: branding, CMS (hero, galeri, FAQ, banner), jam buka, status buka, 6 menu unggulan, ringkasan rating, 6 ulasan terbaru.

**Auth:** tidak ada

**Path:** `{slug}` — slug restoran

**Response `200`:** `RestaurantResource`

| Field | Arti |
|---|---|
| `slug`, `name`, `logo_url`, `theme` | Identitas + warna CMS |
| `headline`, `about_html`, `hero_url`, `how_to_image_url`, `about_image_url` | Profil landing |
| `cta_label`, `cta_url`, `maps_url`, `map_embed_url`, `whatsapp_url` | CTA lokasi / WA |
| `is_open` | Toggle kasir (`outlet.is_open`) |
| `is_open_now` | Apakah sekarang dalam jam buka (jadwal + closed dates) |
| `outlet_name`, `address`, `phone` | Outlet default |
| `today_hours` | Jam hari ini (`day_of_week`, `day_name`, `opens_at`, `closes_at`, `is_closed`) atau `null` |
| `operating_hours` | Jadwal seminggu |
| `closed_dates` | `[{ "date": "Y-m-d", "reason": "..." }]` |
| `gallery` | `{ image_url, caption }` |
| `faqs` | `{ question, answer_html }` |
| `banners` | Promo live: `{ title, subtitle, badge_text, price_label, cta_label, image_url, link_url }` |
| `featured_menu` | Maks 6 item: `id`, `name`, `description`, `price` (setelah diskon), `original_price`, `discount_percent`, `photo_url`, `category_name`, `is_out_of_stock` |
| `rating_summary` | `{ average, count }` |
| `recent_reviews` | Maks 6, bentuk sama `RestaurantReviewResource` |
| `landing` | Layout tenant: `{ sections: [{ id, enabled }], copy: { about: { label, title, highlight, ... }, ... } }`. `copy` sudah digabung dengan default. Urutan `sections` = urutan blok di landing. |

`landing.copy` memakai default jika tenant belum mengubah teks (mis. tentang: `Cerita di balik dapur`). Aplikasi klien harus tetap menghormati data kosong (galeri/FAQ/banner) meski `enabled: true`.

---

## GET `/restaurants/{slug}/menu`

**Kegunaan:** Katalog menu publik (cari, filter kategori, urut harga, paginasi). Tanpa varian/modifier — itu ada di `GET /guest/menu` setelah klaim meja.

**Auth:** tidak ada

**Query**

| Param | Default | Keterangan |
|---|---|---|
| `search` | `""` | Nama menu, spasi diabaikan |
| `category_id` | — | Filter kategori |
| `sort` | `asc` | `asc` / `desc` menurut harga efektif |
| `per_page` | `12` | 1–48 |

**Response `200`**

```json
{
  "data": {
    "categories": [{ "id": 1, "name": "Minuman" }],
    "items": [
      {
        "id": 1,
        "name": "Es Teh",
        "description": null,
        "price": 8000,
        "original_price": 8000,
        "discount_percent": null,
        "photo_url": null,
        "is_out_of_stock": false,
        "category_id": 1,
        "category_name": "Minuman"
      }
    ]
  },
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 12,
    "total": 1
  }
}
```

Tanpa outlet default: `categories`/`items` kosong, `total` 0.

---

## GET `/restaurants/{slug}/reviews`

**Kegunaan:** Daftar ulasan publik, terbaru dulu.

**Auth:** tidak ada

**Query:** `per_page` default `12`, rentang 1–48. Paginasi Laravel (`data` + `links` + `meta`).

**Response `200`:** koleksi `RestaurantReviewResource`

```json
{
  "data": [
    {
      "customer_name": "Budi",
      "rating": 5,
      "comment": "Enak sekali...",
      "submitted_at": "2026-08-18T10:00:00+07:00"
    }
  ]
}
```
