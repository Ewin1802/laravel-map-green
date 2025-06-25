<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'PPTK - EWINLANTAPA',
            'email' => 'pptk@admin.com',
            'password' => Hash::make('89898989'),
            'role' => 'admin',
            'unit' => 'WKDH',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'PPTK Staf',
            'email' => 'pptk@staf.com',
            'password' => Hash::make('11111111'),
            'role' => 'staff',
            'unit' => 'KDH',
        ]);

        \App\Models\Sumberdana::create([
            'sumber_dana' => 'DBH (Dana Bagi Hasil)',
        ]);

        \App\Models\Sumberdana::create([
            'sumber_dana' => 'DAU (Dana Alokasi Umum)',
        ]);

        \App\Models\Sumberdana::create([
            'sumber_dana' => 'DAK (Dana Alokasi Khusus)',
        ]);

        \App\Models\Organisasi::create([
            'kode_organisasi' => '4.01.0.00.0.00.01.0000',
            'desk_organisasi' => 'Sekretariat Daerah',
        ]);

        \App\Models\Program::create([
            'kode_program' => '4.01.01',
            'desk_program' => 'Program Penunjang Urusan Pemerintahan Daerah Kabupaten/Kota',
        ]);

        \App\Models\Program::create([
            'kode_program' => '4.01.02',
            'desk_program' => 'Program Pemerintahan dan Kesejahteraan Rakyat',
        ]);

        \App\Models\Kegiatan::create([
            'kode_kegiatan' => '4.01.01.2.14',
            'desk_kegiatan' => 'Pelaksanaan Protokol dan Komunikasi Pimpinan',
        ]);

        \App\Models\Kegiatan::create([
            'kode_kegiatan' => '4.01.02.2.04',
            'desk_kegiatan' => 'Fasilitasi Kerja Sama Daerah',
        ]);

        \App\Models\Kegiatan::create([
            'kode_kegiatan' => '4.01.01.2.12',
            'desk_kegiatan' => 'Fasilitasi Kerumahtanggaan Sekretariat Daerah',
        ]);

        \App\Models\Subkegiatan::create([
            'kode_subkegiatan' => '4.01.01.2.14.0002',
            'desk_subkegiatan' => 'Fasilitasi Komunikasi Pimpinan',
        ]);

        \App\Models\Subkegiatan::create([
            'kode_subkegiatan' => '4.01.02.2.04.0001',
            'desk_subkegiatan' => 'Fasilitasi Kerja Sama Dalam Negeri',
        ]);

        \App\Models\Subkegiatan::create([
            'kode_subkegiatan' => '4.01.01.2.12.0002',
            'desk_subkegiatan' => 'Penyediaan Kebutuhan Rumah Tangga Wakil Kepala Daerah',
        ]);

        \App\Models\Rekbelanja::create([
            'kode_rek' => '5.2.02.10.01.0002',
            'desk_belanja' => 'Belanja Modal Personal Computer',
            'jenis_belanja' => 'Modal',
        ]);

        \App\Models\Rekbelanja::create([
            'kode_rek' => '5.1.02.04.01.0001',
            'desk_belanja' => 'Belanja Perjalanan Dinas Biasa',
            'jenis_belanja' => 'Jasa',
        ]);
    }
}
