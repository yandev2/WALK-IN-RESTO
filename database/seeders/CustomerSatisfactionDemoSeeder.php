<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Models\RestaurantReview;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerSatisfactionDemoSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::query()->where('slug', 'resto-demo')->first()
            ?? Restaurant::query()->first();

        if (! $restaurant) {
            $this->command?->warn('Restoran demo tidak ditemukan. Jalankan DemoRestaurantSeeder terlebih dahulu.');

            return;
        }

        $outlet = $restaurant->defaultOutlet ?? Outlet::query()->where('restaurant_id', $restaurant->id)->first();
        if (! $outlet) {
            $this->command?->warn('Outlet demo tidak ditemukan.');

            return;
        }

        $tables = DiningTable::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('outlet_id', $outlet->id)
            ->get();

        if ($tables->isEmpty()) {
            $this->command?->warn('Meja tidak ditemukan.');

            return;
        }

        $reviewsData = [
            [
                'name' => 'Budi Pratama',
                'phone' => '081298765432',
                'rating' => 5,
                'comment' => 'Makanannya luar biasa enak! Ayam bakarnya meresap sempurna sampai ke dalam. Sangat praktis pesan via scan QR meja tanpa perlu antre pelayan.',
                'days_ago' => 1,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 145000,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'phone' => '081388776655',
                'rating' => 5,
                'comment' => 'Suasana restorannya sangat cozy untuk santap malam bersama keluarga. Fitur gabung meja pakai PIN 4-digit keren banget, suami dan anak bisa pilih menu sendiri.',
                'days_ago' => 2,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 285000,
            ],
            [
                'name' => 'Reza Rahardian',
                'phone' => '085711223344',
                'rating' => 5,
                'comment' => 'Es Kopi Susu Gula Aren dan Nasi Goreng Spesialnya juara! Pembayaran QRIS cepat dan status masakan di HP akurat banget pas sudah siap.',
                'days_ago' => 3,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 95000,
            ],
            [
                'name' => 'Dewi Lestari',
                'phone' => '081900998877',
                'rating' => 4,
                'comment' => 'Rasa makanan memuaskan dan tempat bersih. Hanya saja pas jam makan siang agak sedikit ramai, tapi makanan keluar tepat waktu.',
                'days_ago' => 4,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 165000,
            ],
            [
                'name' => 'Aris Munandar',
                'phone' => '081234445566',
                'rating' => 5,
                'comment' => 'Staf ramah, toilet bersih, porsi makanan cukup mengenyangkan dengan harga yang ramah di kantong. Pasti akan balik lagi.',
                'days_ago' => 6,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 120000,
            ],
            [
                'name' => 'Amanda Putri',
                'phone' => '087855667788',
                'rating' => 4,
                'comment' => 'Pelayanan cepat dan struk langsung terkirim ke WhatsApp. Praktis tidak perlu simpan kertas struk fisik.',
                'days_ago' => 8,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 85000,
            ],
            [
                'name' => 'Hendra Gunawan',
                'phone' => '081399881122',
                'rating' => 3,
                'comment' => 'Makanannya enak tapi kemarin minuman es teh manisnya agak lama keluar, sekitar 15 menit baru sampai ke meja.',
                'days_ago' => 10,
                'is_published' => true,
                'internal_notes' => 'Telah dievaluasi bersama tim Bar Station untuk prioritaskan antrean minuman saat jam sibuk.',
                'amount' => 110000,
            ],
            [
                'name' => 'Kevin Sanjaya',
                'phone' => '085611223399',
                'rating' => 5,
                'comment' => 'Sop Iga Bakarnya sangat empuk dan kuahnya segar berempah. Sangat recommended untuk makan siang!',
                'days_ago' => 12,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 210000,
            ],
            [
                'name' => 'Maya Kartika',
                'phone' => '081822334455',
                'rating' => 2,
                'comment' => 'Kemarin pesan level pedas 1 tapi datangnya pedas sekali sampai tidak kuat dihabiskan. Mohon lebih teliti membaca catatan varian pedas.',
                'days_ago' => 14,
                'is_published' => true,
                'internal_notes' => 'Sudah dihubungi via WA, manajemen menyampaikan permohonan maaf dan memberikan voucher pengganti untuk kunjungan berikutnya.',
                'amount' => 135000,
            ],
            [
                'name' => 'Bambang Sudirman',
                'phone' => '081122334455',
                'rating' => 5,
                'comment' => 'Konsep self-order meja ini sangat efisien. Tidak perlu panggil pelayan berkali-kali saat mau tambah menu penutup.',
                'days_ago' => 16,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 175000,
            ],
            [
                'name' => 'Fajar Alfian',
                'phone' => '087788990011',
                'rating' => 4,
                'comment' => 'Makanan sedap, wifi kencang, cocok buat santai sore sambil kerja tipis-tipis.',
                'days_ago' => 20,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 95000,
            ],
            [
                'name' => 'Rina Wijaya',
                'phone' => '081377889900',
                'rating' => 1,
                'comment' => 'Pesanan salah satu makanan terlewat tidak terkirim ke meja sampai harus lapor ke kasir. Mohon koordinasi pelayannya ditingkatkan.',
                'days_ago' => 22,
                'is_published' => false,
                'internal_notes' => 'Kasir dan tim runner sudah ditegur. Telah diklarifikasi via WA ke Ibu Rina dan masalah selesai secara kekeluargaan.',
                'amount' => 190000,
            ],
            [
                'name' => 'Dimas Anggara',
                'phone' => '081266778899',
                'rating' => 5,
                'comment' => 'Porsi banyak, rasa mantap, harga bersahabat. Nilai 10/10 untuk hidangan bebek gorengnya!',
                'days_ago' => 25,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 160000,
            ],
            [
                'name' => 'Clarissa Stephanie',
                'phone' => '081911224466',
                'rating' => 4,
                'comment' => 'Dessert cheesecake dan matcha latte nya enak banget! Tempatnya juga estetik buat foto-foto.',
                'days_ago' => 28,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 125000,
            ],
            [
                'name' => 'Toni Setiawan',
                'phone' => '085233445566',
                'rating' => 3,
                'comment' => 'Tempat agak sedikit panas di area indoor bagian ujung, mungkin AC-nya bisa ditambah atau diservis.',
                'days_ago' => 35,
                'is_published' => true,
                'internal_notes' => 'Teknisi AC sudah melakukan servis berkala.',
                'amount' => 90000,
            ],
            [
                'name' => 'Lestari Indah',
                'phone' => '081299001122',
                'rating' => 5,
                'comment' => 'Pengalaman makan siang terbaik di sekitar kantor. Cepat, lezat, dan bersih.',
                'days_ago' => 40,
                'is_published' => true,
                'internal_notes' => null,
                'amount' => 150000,
            ],
        ];

        foreach ($reviewsData as $index => $item) {
            $table = $tables[$index % $tables->count()];
            $submittedAt = now()->subDays($item['days_ago'])->setHour(rand(12, 20))->setMinute(rand(10, 50));
            $cleanPhone = \App\Support\WhatsAppNumber::normalize($item['phone']) ?: $item['phone'];

            // 1. Pastikan data Customer CRM terdaftar
            $customer = Customer::withoutRestaurantScope()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'phone' => $cleanPhone,
                ],
                [
                    'name' => $item['name'],
                    'tier' => $item['rating'] === 5 ? 'gold' : 'reguler',
                    'points_balance' => (int) floor($item['amount'] / 10000),
                    'total_spent' => $item['amount'] * rand(1, 3),
                    'total_orders' => rand(1, 4),
                    'last_visit_at' => $submittedAt,
                ]
            );

            // 2. Buat Visit sesi meja
            $visit = Visit::withoutRestaurantScope()->create([
                'public_id' => (string) Str::ulid(),
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'table_id' => $table->id,
                'status' => 'closed',
                'join_pin' => (string) rand(1000, 9999),
                'customer_name' => $item['name'],
                'customer_wa' => $cleanPhone,
                'claimed_at' => $submittedAt->copy()->subMinutes(60),
                'claim_expires_at' => $submittedAt->copy()->subMinutes(50),
                'closed_at' => $submittedAt,
            ]);

            // 3. Buat Order terkait
            $order = Order::withoutRestaurantScope()->create([
                'public_id' => (string) Str::ulid(),
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'visit_id' => $visit->id,
                'number' => 100 + $index,
                'idempotency_key' => (string) Str::uuid(),
                'status' => 'completed',
                'source' => 'guest',
                'currency' => 'IDR',
                'pb1_pct_snapshot' => 10,
                'service_pct_snapshot' => 5,
                'tax_mode_snapshot' => 'exclusive',
                'subtotal' => (int) round($item['amount'] * 0.85),
                'discount_amount' => 0,
                'points_redeemed' => 0,
                'service_amount' => (int) round($item['amount'] * 0.05),
                'pb1_amount' => (int) round($item['amount'] * 0.10),
                'grand_before' => $item['amount'],
                'grand_payable' => $item['amount'],
                'payment_method' => $index % 2 === 0 ? 'qris' : 'cash',
                'receipt_wa_snapshot' => $cleanPhone,
                'paid_at' => $submittedAt->copy()->subMinutes(45),
            ]);

            // 4. Buat Ulasan Pelanggan
            RestaurantReview::withoutRestaurantScope()->create([
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'visit_id' => $visit->id,
                'order_id' => $order->id,
                'customer_name' => $item['name'],
                'rating' => $item['rating'],
                'comment' => $item['comment'],
                'is_published' => $item['is_published'],
                'internal_notes' => $item['internal_notes'],
                'submitted_at' => $submittedAt,
            ]);
        }

        $this->command?->info('Berhasil men-seed '.count($reviewsData).' ulasan pelanggan & analitik kepuasan untuk Resto Demo.');
    }
}
