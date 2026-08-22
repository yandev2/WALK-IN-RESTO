# API v1

Base URL: `/api/v1`

Semua endpoint di bawah prefix ini. JSON Laravel Resource dibungkus `{ "data": ... }`. Error validasi memakai format Laravel (`message` + `errors`). Error guest memakai `code` (`device_required`, `visit_required`, `visit_expired`).

## Auth & header

| Header | Kegunaan |
|---|---|
| `X-Guest-Device` | Token perangkat tamu. Alternatif: `Authorization: Bearer {token}` |
| `Idempotency-Key` | Wajib di checkout (atau field body `idempotency_key`) |
| `Accept` | `application/json` |
| `Content-Type` | `application/json` (kecuali upload bukti: `multipart/form-data`) |

Token didapat dari `POST /guest/session`. Simpan dan kirim di request guest berikutnya.

## Middleware

| Alias | Efek |
|---|---|
| `throttle:60,1` | Semua v1: 60 request / menit |
| `identify.api.guest` | Baca token dari header (opsional) |
| `require.api.guest` | 401 jika token kosong |
| `guest.api.visit` | 401 jika belum punya visit `open` |
| `throttle:10,1` | Claim, join, checkout, submit review |

## Alur guest

1. `POST /guest/session` → simpan `device_token`
2. `GET /guest/tables/{token}` → cek mode QR (`claim` / `join` / `ready` / `blocked` / `invalid`)
3. `POST .../claim` (tamu pertama) atau `POST .../join` (rombongan + PIN)
4. Menu / cart / checkout / orders / review memakai visit yang sedang `open`

## Index

### Publik (tanpa login)

- [GET /restaurants](public.md#get-restaurants) — daftar resto aktif
- [GET /restaurants/{slug}](public.md#get-restaurantsslug) — landing (branding, jam, rating, review)
- [GET /restaurants/{slug}/menu](public.md#get-restaurantsslugmenu) — katalog menu
- [GET /restaurants/{slug}/reviews](public.md#get-restaurantsslugreviews) — ulasan paginasi

### Guest

- [POST /guest/session](guest.md#post-guestsession) — buat / kembalikan token perangkat
- [GET /guest/tables/{token}](guest.md#get-guesttablestoken) — inspect QR meja
- [POST /guest/tables/{token}/claim](guest.md#post-guesttablestokenclaim) — klaim meja
- [POST /guest/tables/{token}/join](guest.md#post-guesttablestokenjoin) — gabung rombongan
- [GET /guest/visit](guest.md#get-guestvisit) — sesi meja aktif + branding
- [GET /guest/menu](guest.md#get-guestmenu) — menu outlet visit (varian + modifier)
- [GET /guest/cart](guest.md#get-guestcart) — isi keranjang + total pajak/service
- [POST /guest/cart/items](guest.md#post-guestcartitems) — tambah item
- [PATCH /guest/cart/items/{id}](guest.md#patch-guestcartitemsid) — ubah qty
- [DELETE /guest/cart/items/{id}](guest.md#delete-guestcartitemsid) — hapus item
- [POST /guest/checkout](guest.md#post-guestcheckout) — buat pesanan
- [GET /guest/orders](guest.md#get-guestorders) — daftar pesanan visit
- [GET /guest/orders/{public_id}](guest.md#get-guestorderspublic_id) — detail pesanan
- [POST /guest/orders/{public_id}/proof](guest.md#post-guestorderspublic_idproof) — unggah bukti bayar
- [GET /guest/review](guest.md#get-guestreview) — status portal ulasan
- [POST /guest/review](guest.md#post-guestreview) — kirim ulasan
