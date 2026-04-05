<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'Sekretariat Daerah Kota Bengkulu',
            'Sekretariat Dewan Perwakilan Rakyat Daerah Kota Bengkulu',
            'Inspektorat Kota Bengkulu',
            'Satuan Polisi Pamong Praja Kota Bengkulu',
            'Badan Penanggulangan Bencana Daerah Kota Bengkulu',
            'Badan Perencanaan Pembangunan Daerah Kota Bengkulu',
            'Badan Pendapatan Daerah Kota Bengkulu',
            'Badan Pengelolaan Keuangan dan Aset Daerah Kota Bengkulu',
            'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia Kota Bengkulu',
            'Badan Kesatuan Bangsa dan Politik Kota Bengkulu',
            'Dinas Pendidikan dan Kebudayaan Kota Bengkulu',
            'Dinas Pangan dan Pertanian Kota Bengkulu',
            'Dinas Kependudukan dan Pencatatan Sipil Kota Bengkulu',
            'Dinas Lingkungan Hidup Kota Bengkulu',
            'Dinas Pemberdayaan Perempuan, Perlindungan Anak, Pengendalian Penduduk dan Keluarga Berencana Kota Bengkulu',
            'Dinas Pariwisata Kota Bengkulu',
            'Dinas Pemudaan dan Olahraga Kota Bengkulu',
            'Dinas Perdagangan dan Perindustrian Kota Bengkulu',
            'Dinas Koperasi, Usaha Kecil dan Menengah Kota Bengkulu',
            'Dinas Sosial Kota Bengkulu',
            'Dinas Perhubungan Kota Bengkulu',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Bengkulu',
            'Dinas Perikanan Kota Bengkulu',
            'Dinas Kesehatan Kota Bengkulu',
            'Dinas Pekerjaan Umum dan Penataan Ruang Kota Bengkulu',
            'Dinas Pemadaman Kebakaran dan Penyelamatan Kota Bengkulu',
            'Dinas Kearsipan dan Perpustakaan Kota Bengkulu',
            'Dinas Perumahan Rakyat dan Kawasan Permukiman dan Pertanahan Kota Bengkulu',
            'Dinas Ketenagakerjaan Kota Bengkulu',
            'Dinas Komunikasi dan Informatika Kota Bengkulu',
        ];

        foreach ($units as $name) {
            Unit::firstOrCreate([
                'nama_unit' => $name,
            ], [
                'type' => 'external',
            ]);
        }
    }
}
