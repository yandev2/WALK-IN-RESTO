<?php

return [
    'menu' => [
        'label' => 'Profil',
    ],

    'widget' => [
        'profile' => [
            'label' => 'Profil',
        ],
    ],

    'fields' => [
        'current_password' => 'Password saat ini',
        'password' => 'Password baru',
        'password_confirmation' => 'Konfirmasi password',
    ],

    'sections' => [
        'profile' => [
            'title' => 'Informasi profil',
            'description' => 'Perbarui nama, email, dan foto profil akun Anda.',
            'actions' => [
                'save' => 'Simpan',
            ],
        ],
        'password' => [
            'title' => 'Ubah password',
            'description' => 'Gunakan password yang kuat dan jangan bagikan ke orang lain.',
            'actions' => [
                'save' => 'Simpan',
            ],
        ],
        'sessions' => [
            'title' => 'Sesi browser',
            'description' => 'Lihat perangkat yang sedang masuk dan keluarkan sesi lain jika perlu.',
            'intro' => 'Jika perlu, Anda bisa mengeluarkan sesi di perangkat lain. Daftar di bawah mungkin tidak lengkap. Jika akun terasa tidak aman, ubah password juga.',
            'empty' => 'Tidak ada sesi browser lain. Daftar ini hanya tampil jika sesi disimpan di database.',
            'unknown' => 'Tidak diketahui',
            'this_device' => 'Perangkat ini',
            'last_active' => 'Terakhir aktif',
            'confirm_logout_session' => 'Yakin ingin mengeluarkan sesi browser ini?',
            'columns' => [
                'device' => 'Perangkat',
                'ip_address' => 'Alamat IP',
                'last_active' => 'Terakhir aktif',
                'actions' => 'Aksi',
            ],
            'actions' => [
                'logout' => 'Keluar',
                'logout_others' => 'Keluar dari perangkat lain',
            ],
            'modal' => [
                'heading' => 'Keluar dari sesi browser lain',
                'description' => 'Masukkan password untuk mengonfirmasi Anda ingin mengeluarkan sesi di perangkat lain.',
            ],
        ],
        'delete' => [
            'title' => 'Hapus akun',
            'description' => 'Hapus akun secara permanen.',
            'warning' => [
                'heading' => 'Tindakan ini permanen',
                'description' => 'Setelah akun dihapus, data terkait tidak bisa dikembalikan.',
            ],
            'actions' => [
                'delete' => 'Hapus akun',
            ],
            'modal' => [
                'heading' => 'Hapus akun',
                'description' => 'Yakin ingin menghapus akun? Data tidak bisa dikembalikan.',
            ],
        ],
    ],

    'notifications' => [
        'profile_updated' => 'Profil berhasil disimpan.',
        'password_updated' => 'Password berhasil diubah.',
        'other_browser_sessions_logged_out' => 'Sesi browser lain sudah dikeluarkan.',
        'browser_session_logged_out' => 'Sesi browser sudah dikeluarkan.',
    ],
];
