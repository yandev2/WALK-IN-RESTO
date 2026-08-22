# Guest

Controller: `App\Http\Controllers\Api\V1\Guest`.

Token perangkat: header `X-Guest-Device` atau `Authorization: Bearer {token}`.

Objek bersama ada di [README.md](README.md) (auth) dan bagian bawah file ini (visit, cart, order, brand).

---

## POST `/guest/session`

**Kegunaan:** Membuat atau mengembalikan token perangkat. Titik masuk semua alur guest.

**Middleware:** `identify.api.guest` (token opsional)

**Body:** tidak ada. Jika header sudah berisi token, token itu dikembalikan; jika tidak, server membuat token 64 karakter.

**Response `200`**

```json
{ "data": { "device_token": "..." } }
```

---

## GET `/guest/tables/{token}`

**Kegunaan:** Inspect stiker QR meja: apakah bisa klaim, join PIN, sudah di meja, diblokir, atau token invalid. Juga mengembalikan branding resto.

**Middleware:** `identify.api.guest`

**Path:** `{token}` — karakter `A-Za-z0-9_-`

**Response `200`:** `TableScanResource`

| Field | Arti |
|---|---|
| `mode` | `claim` / `join` / `ready` / `blocked` / `invalid` |
| `message` | Teks untuk UI |
| `table_code` | Kode meja, atau `null` jika invalid |
| `restaurant` | Branding (`slug`, `name`, `logo_url`, `theme`) |
| `visit` | Hanya jika `mode=ready`: objek visit lengkap |

| `mode` | Artinya | Langkah berikutnya |
|---|---|---|
| `claim` | Meja kosong, resto buka | `POST .../claim` |
| `join` | Sudah ada tamu | `POST .../join` + PIN 4 digit |
| `ready` | Device ini sudah di visit meja | `GET /guest/visit` / menu / cart |
| `blocked` | Tutup, OOS, atau sedang dibersihkan | Tampilkan `message` |
| `invalid` | Stiker tidak dikenal / diganti | Hubungi kasir |

---

## POST `/guest/tables/{token}/claim`

**Kegunaan:** Tamu pertama membuka visit di meja (WA wajib). Mengembalikan PIN rombongan.

**Middleware:** `identify.api.guest` + `require.api.guest` + throttle 10/menit

**Body**

| Field | Wajib | Aturan |
|---|---|---|
| `customer_wa` | ya | string, max 20 |
| `customer_name` | tidak | string, max 120 |

**Response `201`:** `VisitResource`  
**422:** token QR invalid, resto tutup, meja tidak bisa diklaim, dll.

---

## POST `/guest/tables/{token}/join`

**Kegunaan:** Device lain bergabung ke visit yang sudah terbuka, memakai PIN 4 digit dari host.

**Middleware:** sama claim (token wajib + throttle 10/menit)

**Body**

| Field | Wajib | Aturan |
|---|---|---|
| `pin` | ya | tepat 4 digit `0-9` |

**Response `200`:** `VisitResource`  
**422:** token invalid, PIN salah, meja tidak dalam mode join.

---

## GET `/guest/visit`

**Kegunaan:** Sesi meja aktif: status, PIN, data tamu, meja, branding resto, apakah Fonnte aktif, status portal ulasan.

**Middleware:** `guest.api.visit`

**Response `200`:** `VisitResource` (lihat [Visit](#visitresource))

---

## GET `/guest/menu`

**Kegunaan:** Menu lengkap outlet visit (kategori → item → varian + grup modifier). Dipakai setelah duduk, untuk pesan.

**Middleware:** `guest.api.visit`

**Response `200`:** koleksi `MenuCategoryResource`

```json
{
  "data": [
    {
      "id": 1,
      "name": "Minuman",
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
          "category_name": "Minuman",
          "variants": [{ "id": 1, "name": "Jumbo", "price_delta": 2000 }],
          "modifier_groups": [
            {
              "id": 1,
              "name": "Topping",
              "min_select": 0,
              "max_select": 2,
              "is_required": false,
              "modifiers": [{ "id": 1, "name": "Boba", "price": 3000 }]
            }
          ]
        }
      ]
    }
  ]
}
```

Hanya kategori/item/varian/modifier `is_active`. `price` = harga efektif (setelah diskon).

---

## GET `/guest/cart`

**Kegunaan:** Isi keranjang visit + rincian total **sebelum** unique add QRIS (0–999). Paritas dengan preview checkout di web.

**Middleware:** `guest.api.visit`

**Response `200`:** `CartResource`

| Field | Arti |
|---|---|
| `subtotal` | Jumlah line item (integer IDR) |
| `service_pct` | % service charge outlet |
| `pb1_pct` | % PB1 outlet |
| `tax_mode` | biasanya `exclusive` |
| `service_amount` | `round(subtotal * service_pct / 100)` |
| `pb1_amount` | `round((subtotal + service) * pb1_pct / 100)` |
| `grand_before` | `subtotal + service_amount + pb1_amount` (belum unique add) |
| `items` | lihat [Cart item](#cartitemresource) |

---

## POST `/guest/cart/items`

**Kegunaan:** Tambah menu ke keranjang (qty, catatan, varian, modifier).

**Middleware:** `guest.api.visit`

**Body**

| Field | Wajib | Aturan |
|---|---|---|
| `menu_item_id` | ya | exists `menu_items` |
| `menu_variant_id` | tidak | exists `menu_variants` |
| `qty` | tidak | integer ≥ 1, default 1 |
| `notes` | tidak | max 255 |
| `modifier_ids` | tidak | array id `modifiers` |

**Response `201`:** `CartItemResource` (item yang baru ditambah, bukan cart penuh)

---

## PATCH `/guest/cart/items/{cartItem}`

**Kegunaan:** Ubah qty. `qty = 0` menghapus baris.

**Middleware:** `guest.api.visit`

**Path:** id `visit_cart_items`. Bukan milik visit ini → `404`.

**Body:** `{ "qty": 2 }` — integer ≥ 0, wajib

**Response `200`:** `CartResource` (keranjang penuh + totals)

---

## DELETE `/guest/cart/items/{cartItem}`

**Kegunaan:** Hapus satu baris keranjang.

**Middleware:** `guest.api.visit`

**Response `200`:** `CartResource`

---

## POST `/guest/checkout`

**Kegunaan:** Ubah keranjang jadi pesanan (QRIS atau tunai). Idempoten: kunci sama mengembalikan order yang sama.

**Middleware:** `guest.api.visit` + throttle 10/menit

**Header:** `Idempotency-Key` wajib (atau body `idempotency_key`, max 64)

**Body**

| Field | Wajib | Aturan |
|---|---|---|
| `method` | ya | `qris` atau `cash` |
| `send_receipt` | tidak | boolean |
| `idempotency_key` | jika header kosong | max 64 |
| `gps` | cash / geofence | `{ lat, lng, accuracy, gps_status }` |

**Response `201`:** `OrderResource`  
**422:** keranjang kosong, kunci idempotensi hilang, GPS di luar geofence (cash), validasi lain.

`grand_payable` untuk QRIS = `grand_before` + `unique_add` (0–999). Tunai tidak memakai unique add.

---

## GET `/guest/orders`

**Kegunaan:** Semua pesanan visit aktif, terbaru dulu.

**Middleware:** `guest.api.visit`

**Response `200`:** koleksi `OrderResource`

---

## GET `/guest/orders/{public_id}`

**Kegunaan:** Detail satu pesanan (item, status KDS, pembayaran, QRIS, bukti).

**Middleware:** `guest.api.visit`

**Path:** `orders.public_id`. Bukan milik visit → `403`.

**Response `200`:** `OrderResource`

---

## POST `/guest/orders/{public_id}/proof`

**Kegunaan:** Unggah foto bukti transfer/QRIS untuk pembayaran yang menunggu.

**Middleware:** `guest.api.visit`

**Body:** `multipart/form-data`

| Field | Wajib | Aturan |
|---|---|---|
| `proof` | ya | file, max 5 MB, JPEG / PNG / WEBP / HEIC / HEIF |

**Response `200`:** `PaymentResource`  
**403:** order bukan milik visit.

---

## GET `/guest/review`

**Kegunaan:** Cek apakah portal ulasan terbuka, apakah sudah bisa submit, dan apakah sudah pernah review.

**Middleware:** `guest.api.visit`

**Response `200`**

```json
{
  "data": {
    "portal_open": true,
    "can_submit": true,
    "submitted": false,
    "review": null
  }
}
```

`review` terisi `RestaurantReviewResource` jika sudah submit.

---

## POST `/guest/review`

**Kegunaan:** Kirim ulasan (rating + komentar) untuk resto visit. Satu visit sekali.

**Middleware:** `guest.api.visit` + throttle 10/menit

**Body**

| Field | Wajib | Aturan |
|---|---|---|
| `rating` | ya | integer 1–5 |
| `comment` | ya | 10–1000 karakter |

**Response `201`:** `RestaurantReviewResource`  
**422:** portal tertutup, sudah submit, atau validasi field.

---

## Bentuk objek

### VisitResource

```json
{
  "public_id": "...",
  "status": "open",
  "join_pin": "1234",
  "customer_wa": "0812...",
  "customer_name": "Budi",
  "table_code": "1",
  "restaurant_slug": "resto-demo",
  "restaurant": {
    "slug": "resto-demo",
    "name": "Resto Demo",
    "logo_url": "https://...",
    "theme": { "primary": "#F97316", "primary_dark": "#D66313", "accent": "#FB923C" }
  },
  "claim_expires_at": "...",
  "has_fonnte": false,
  "review": {
    "portal_open": false,
    "can_submit": false,
    "submitted": false,
    "review": null
  }
}
```

`restaurant` dipakai UI guest untuk logo/nama/warna tanpa panggil landing publik lagi.

### CartItemResource

```json
{
  "id": 1,
  "menu_item_id": 10,
  "menu_variant_id": null,
  "name": "Es Teh",
  "variant_name": null,
  "qty": 2,
  "notes": null,
  "line_total": 16000,
  "modifiers": [{ "id": 1, "name": "Boba", "price": 3000 }]
}
```

### OrderResource

| Field | Arti |
|---|---|
| `public_id`, `number`, `status` | Identitas + status order |
| `payment_method` | `qris` / `cash` |
| `subtotal`, `service_amount`, `pb1_amount`, `grand_before`, `grand_payable` | Total tersimpan |
| `send_receipt` | minta struk WA |
| `items[]` | snapshot nama, qty, `unit_price`, `notes`, `kds_status`, `timer_minutes`, `timer_band`, `modifiers` |
| `payment` | pembayaran terbaru atau `null` |

### PaymentResource

| Field | Arti |
|---|---|
| `method`, `status`, `amount` | Metode, status kasir, nominal |
| `unique_add` | Tambahan unik QRIS (0 jika cash) |
| `qris_image_url` | Gambar QRIS snapshot |
| `proof_image_url` | Bukti unggahan tamu |
| `gps_status` | Status geofence saat checkout |
| `reject_reason` | Alasan tolak kasir |
| `awaiting_expires_at` | Batas menunggu konfirmasi |
