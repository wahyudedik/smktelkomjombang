<?php

namespace Database\Seeders;

use App\Models\KategoriSarpras;
use App\Models\Barang;
use App\Models\Ruang;
use App\Models\Maintenance;
use App\Models\Sarana;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SarprasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Kategori Sarpras
        $kategoris = [
            [
                'nama_kategori' => 'Elektronik',
                'kode_kategori' => 'ELEK',
                'deskripsi' => 'Perangkat elektronik dan teknologi',
                'sort_order' => 1,
            ],
            [
                'nama_kategori' => 'Furnitur',
                'kode_kategori' => 'FURN',
                'deskripsi' => 'Meja, kursi, dan perabot lainnya',
                'sort_order' => 2,
            ],
            [
                'nama_kategori' => 'Alat Tulis',
                'kode_kategori' => 'ATK',
                'deskripsi' => 'Alat tulis kantor dan perlengkapan sekolah',
                'sort_order' => 3,
            ],
            [
                'nama_kategori' => 'Olahraga',
                'kode_kategori' => 'OLAH',
                'deskripsi' => 'Peralatan olahraga dan kebugaran',
                'sort_order' => 4,
            ],
            [
                'nama_kategori' => 'Laboratorium',
                'kode_kategori' => 'LAB',
                'deskripsi' => 'Peralatan laboratorium dan sains',
                'sort_order' => 5,
            ],
        ];

        foreach ($kategoris as $kategori) {
            KategoriSarpras::create($kategori);
        }

        // Create Ruang
        $ruangs = [
            [
                'kode_ruang' => 'R001',
                'nama_ruang' => 'Laboratorium Komputer 1',
                'jenis_ruang' => 'laboratorium',
                'luas_ruang' => 60.5,
                'kapasitas' => 30,
                'lantai' => '1',
                'gedung' => 'Gedung A',
                'kondisi' => 'baik',
                'status' => 'aktif',
                'fasilitas' => ['AC', 'Proyektor', 'Internet', 'Komputer'],
                'catatan' => 'Ruang laboratorium komputer dengan 30 unit PC',
            ],
            [
                'kode_ruang' => 'R002',
                'nama_ruang' => 'Laboratorium Fisika',
                'jenis_ruang' => 'laboratorium',
                'luas_ruang' => 45.0,
                'kapasitas' => 25,
                'lantai' => '2',
                'gedung' => 'Gedung B',
                'kondisi' => 'baik',
                'status' => 'aktif',
                'fasilitas' => ['AC', 'Meja Laboratorium', 'Alat Fisika'],
                'catatan' => 'Laboratorium untuk praktikum fisika',
            ],
            [
                'kode_ruang' => 'R003',
                'nama_ruang' => 'Perpustakaan',
                'jenis_ruang' => 'perpustakaan',
                'luas_ruang' => 120.0,
                'kapasitas' => 100,
                'lantai' => '1',
                'gedung' => 'Gedung C',
                'kondisi' => 'baik',
                'status' => 'aktif',
                'fasilitas' => ['AC', 'Rak Buku', 'Meja Baca', 'Internet'],
                'catatan' => 'Perpustakaan sekolah dengan koleksi lengkap',
            ],
            [
                'kode_ruang' => 'R004',
                'nama_ruang' => 'Aula Serbaguna',
                'jenis_ruang' => 'aula',
                'luas_ruang' => 200.0,
                'kapasitas' => 300,
                'lantai' => '1',
                'gedung' => 'Gedung D',
                'kondisi' => 'baik',
                'status' => 'aktif',
                'fasilitas' => ['AC', 'Panggung', 'Sound System', 'Proyektor'],
                'catatan' => 'Aula untuk acara-acara sekolah',
            ],
            [
                'kode_ruang' => 'R005',
                'nama_ruang' => 'Lapangan Basket',
                'jenis_ruang' => 'lapangan',
                'luas_ruang' => 420.0,
                'kapasitas' => 50,
                'lantai' => '0',
                'gedung' => 'Luar Gedung',
                'kondisi' => 'baik',
                'status' => 'aktif',
                'fasilitas' => ['Ring Basket', 'Lantai Beton', 'Pencahayaan'],
                'catatan' => 'Lapangan basket outdoor',
            ],
        ];

        foreach ($ruangs as $ruang) {
            Ruang::create($ruang);
        }

        // Create Barang
        $barangs = [
            [
                'kode_barang' => 'B001',
                'nama_barang' => 'Komputer Desktop',
                'kategori_id' => 1, // Elektronik
                'merk' => 'Dell',
                'model' => 'OptiPlex 7090',
                'serial_number' => 'DL001234',
                'harga_beli' => 8500000,
                'tanggal_pembelian' => '2023-01-15',
                'sumber_dana' => 'BOS',
                'kondisi' => 'baik',
                'lokasi' => 'Laboratorium Komputer 1',
                'ruang_id' => 1,
                'status' => 'tersedia',
                'catatan' => 'Komputer untuk pembelajaran',
            ],
            [
                'kode_barang' => 'B002',
                'nama_barang' => 'Proyektor',
                'kategori_id' => 1, // Elektronik
                'merk' => 'Epson',
                'model' => 'PowerLite 1781W',
                'serial_number' => 'EP002345',
                'harga_beli' => 3500000,
                'tanggal_pembelian' => '2023-02-20',
                'sumber_dana' => 'BOS',
                'kondisi' => 'baik',
                'lokasi' => 'Laboratorium Komputer 1',
                'ruang_id' => 1,
                'status' => 'tersedia',
                'catatan' => 'Proyektor untuk presentasi',
            ],
            [
                'kode_barang' => 'B003',
                'nama_barang' => 'Meja Guru',
                'kategori_id' => 2, // Furnitur
                'merk' => 'IKEA',
                'model' => 'BEKANT',
                'serial_number' => 'IK003456',
                'harga_beli' => 1200000,
                'tanggal_pembelian' => '2023-03-10',
                'sumber_dana' => 'BOS',
                'kondisi' => 'baik',
                'lokasi' => 'Kelas X IPA 1',
                'ruang_id' => null,
                'status' => 'tersedia',
                'catatan' => 'Meja untuk guru di kelas',
            ],
            [
                'kode_barang' => 'B004',
                'nama_barang' => 'Bola Basket',
                'kategori_id' => 4, // Olahraga
                'merk' => 'Spalding',
                'model' => 'TF-1000',
                'serial_number' => 'SP004567',
                'harga_beli' => 450000,
                'tanggal_pembelian' => '2023-04-05',
                'sumber_dana' => 'BOS',
                'kondisi' => 'baik',
                'lokasi' => 'Gudang Olahraga',
                'ruang_id' => null,
                'status' => 'tersedia',
                'catatan' => 'Bola basket untuk olahraga',
            ],
            [
                'kode_barang' => 'B005',
                'nama_barang' => 'Mikroskop',
                'kategori_id' => 5, // Laboratorium
                'merk' => 'Olympus',
                'model' => 'CX23',
                'serial_number' => 'OL005678',
                'harga_beli' => 2500000,
                'tanggal_pembelian' => '2023-05-12',
                'sumber_dana' => 'BOS',
                'kondisi' => 'baik',
                'lokasi' => 'Laboratorium Biologi',
                'ruang_id' => 2,
                'status' => 'tersedia',
                'catatan' => 'Mikroskop untuk praktikum biologi',
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }

        // Get superadmin user for maintenance user_id
        $superadmin = \App\Models\User::whereHas('roles', function ($q) {
            $q->where('name', 'superadmin');
        })->first();

        if (!$superadmin) {
            $this->command->warn('Superadmin user not found. Please run UserSeeder first.');
            return;
        }

        // Create Maintenance
        $maintenances = [
            [
                'kode_maintenance' => 'MTN-ABC12345',
                'jenis_item' => 'barang',
                'item_id' => 1, // Komputer Desktop
                'jenis_maintenance' => 'rutin',
                'deskripsi_masalah' => 'Pembersihan rutin dan update software',
                'tindakan_perbaikan' => 'Membersihkan debu, update antivirus, dan defrag harddisk',
                'tanggal_maintenance' => '2024-01-15',
                'tanggal_selesai' => '2024-01-15',
                'status' => 'selesai',
                'biaya' => 0,
                'teknisi' => 'Ahmad Teknisi',
                'catatan' => 'Maintenance rutin bulanan',
                'user_id' => $superadmin->id,
            ],
            [
                'kode_maintenance' => 'MTN-DEF67890',
                'jenis_item' => 'ruang',
                'item_id' => 1, // Laboratorium Komputer 1
                'jenis_maintenance' => 'pembersihan',
                'deskripsi_masalah' => 'Pembersihan ruang laboratorium',
                'tindakan_perbaikan' => 'Mengepel lantai, membersihkan meja, dan merapikan kabel',
                'tanggal_maintenance' => '2024-01-20',
                'tanggal_selesai' => '2024-01-20',
                'status' => 'selesai',
                'biaya' => 0,
                'teknisi' => 'Petugas Kebersihan',
                'catatan' => 'Pembersihan rutin ruang',
                'user_id' => $superadmin->id,
            ],
            [
                'kode_maintenance' => 'MTN-GHI11111',
                'jenis_item' => 'barang',
                'item_id' => 2, // Proyektor
                'jenis_maintenance' => 'perbaikan',
                'deskripsi_masalah' => 'Lampu proyektor mati',
                'tindakan_perbaikan' => 'Mengganti lampu proyektor dan membersihkan lensa',
                'tanggal_maintenance' => '2024-02-10',
                'tanggal_selesai' => '2024-02-12',
                'status' => 'selesai',
                'biaya' => 500000,
                'teknisi' => 'Teknisi Elektronik',
                'catatan' => 'Perbaikan lampu proyektor',
                'user_id' => $superadmin->id,
            ],
        ];

        foreach ($maintenances as $maintenance) {
            Maintenance::create($maintenance);
        }

        // Create Sarana (Inventory/Facilities Management)
        $saranas = [
            [
                'ruang_id' => 1, // Laboratorium Komputer 1
                'sumber_dana' => 'BOS',
                'kode_sumber_dana' => 'MAUDU/2024',
                'tanggal' => '2024-01-15',
                'catatan' => 'Inventarisasi peralatan laboratorium komputer',
            ],
            [
                'ruang_id' => 2, // Laboratorium Fisika
                'sumber_dana' => 'BOS',
                'kode_sumber_dana' => 'MAUDU/2024',
                'tanggal' => '2024-02-20',
                'catatan' => 'Inventarisasi peralatan laboratorium fisika',
            ],
        ];

        foreach ($saranas as $index => $saranaData) {
            $sarana = Sarana::create($saranaData);
            
            // Attach barang to sarana based on ruang_id
            if ($sarana->ruang_id == 1) {
                // Laboratorium Komputer 1 - attach Komputer Desktop dan Proyektor
                $sarana->barang()->attach([
                    1 => ['jumlah' => 1, 'kondisi' => 'baik'], // Komputer Desktop
                    2 => ['jumlah' => 1, 'kondisi' => 'baik'], // Proyektor
                ]);
                
                // Update ruang_id for attached barang
                Barang::whereIn('id', [1, 2])->update(['ruang_id' => 1]);
            } elseif ($sarana->ruang_id == 2) {
                // Laboratorium Fisika - attach Mikroskop
                $sarana->barang()->attach([
                    5 => ['jumlah' => 1, 'kondisi' => 'baik'], // Mikroskop
                ]);
                
                // Update ruang_id for attached barang
                Barang::whereIn('id', [5])->update(['ruang_id' => 2]);
            }
            
            // Generate kode inventaris
            $lastSarana = Sarana::orderBy('id', 'desc')->where('id', '!=', $sarana->id)->first();
            $no = $lastSarana ? $lastSarana->id + 1 : $sarana->id;
            
            $firstBarang = $sarana->barang()->first();
            $totalJumlah = $sarana->barang()->sum('sarana_barang.jumlah') ?? 1;
            
            $sarana->kode_inventaris = $sarana->generateKodeInventaris(
                $no,
                $totalJumlah,
                $firstBarang ? $firstBarang->kode_barang : null
            );
            $sarana->save();
        }

        $this->command->info('Sarpras seeder completed successfully!');
        $this->command->info('Created ' . count($saranas) . ' sarana records.');
    }
}
