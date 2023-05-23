<?php

use Illuminate\Database\Seeder;
use App\EventCategory;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => ucwords('AGENDA PERTEMUAN'),'cby' => 35],
            ['name' => ucwords('Bazar Minyak Goreng Curah Murah'),'cby' => 35],
            ['name' => ucwords('BUKA BERSAMA JARINGAN DULUR KANG ASEP AWALUDIN'),'cby' => 35],
            ['name' => ucwords('Bukber desa sekecamatan wanasalam tgl 10 april 2022'),'cby' => 35],
            ['name' => ucwords('Bukber RT Se Desa Muara 9 April 2022'),'cby' => 35],
            ['name' => ucwords('Kujungan ke kp.polotot'),'cby' => 35],
            ['name' => ucwords('Kunjungan AAW'),'cby' => 35],
            ['name' => ucwords('Kunjungan Silaturahim Kecamatan Cilograng'),'cby' => 35],
            ['name' => ucwords('Pemasanan Baliho'),'cby' => 35],
            ['name' => ucwords('Pemasangan  Bendera'),'cby' => 35],
            ['name' => ucwords('pemasangan kamrud'),'cby' => 35],
            ['name' => ucwords('Pemasangan Sepanduk'),'cby' => 35],
            ['name' => ucwords('PEMBAGIA HAMPERS'),'cby' => 35],
            ['name' => ucwords('Pembagian Hampers/THR'),'cby' => 35],
            ['name' => ucwords('Pembagian sarung BHS'),'cby' => 35],
            ['name' => ucwords('Pembagian Sarung Jalur AAW Dapil 4'),'cby' => 35],
            ['name' => ucwords('Pembagian Sarung Jalur AAW Dapil 5'),'cby' => 35],
            ['name' => ucwords('Pembentukan Kordes dan korcam'),'cby' => 35],
            ['name' => ucwords('Pemberian Beras Jalur AAW'),'cby' => 35],
            ['name' => ucwords('Pemberian Bingkisan'),'cby' => 35],
            ['name' => ucwords('Pemberian sumbangan'),'cby' => 35],
            ['name' => ucwords('PEMBUATAN BENDERA'),'cby' => 35],
            ['name' => ucwords('Pertemuan dengan 2 jaro di davil 1'),'cby' => 35],
            ['name' => ucwords('Santunan anak yatim se desa Muara'),'cby' => 35],
            ['name' => ucwords('Seren Tahun Kàsepuhan Olot Omik'),'cby' => 35],
            ['name' => ucwords('Silaturahmi alal bihalal dgn kepaLa desa se kecamatan Wassalam'),'cby' => 35],
            ['name' => ucwords('Silaturahmi ke dulur aaw di Cihara'),'cby' => 35],
            ['name' => ucwords('Silaturahmi ke dulur aaw di Pangarangan'),'cby' => 35],
            ['name' => ucwords('Silaturahmi ke dulur aaw kec Cilograng'),'cby' => 35],
            ['name' => ucwords('Silaturahmi ke dulur aaw kec. Bayah'),'cby' => 35],
            ['name' => ucwords('Silaturahmi ke dulur aaw kec. Cibeber'),'cby' => 35],
        ];

        foreach ($data as $value) {
            EventCategory::create($value);
        }
    }
}
