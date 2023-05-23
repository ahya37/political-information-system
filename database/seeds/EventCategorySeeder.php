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
            ['name' => 'AGENDA PERTEMUAN','cby' => 35],
            ['name' => 'Bazar Minyak Goreng Curah Murah','cby' => 35],
            ['name' => 'BUKA BERSAMA JARINGAN DULUR KANG ASEP AWALUDIN','cby' => 35],
            ['name' => 'Bukber desa sekecamatan wanasalam tgl 10 april 2022','cby' => 35],
            ['name' => 'Bukber RT Se Desa Muara 9 April 2022','cby' => 35],
            ['name' => 'Kujungan ke kp.polotot','cby' => 35],
            ['name' => 'Kunjungan AAW','cby' => 35],
            ['name' => 'Kunjungan Silaturahim Kecamatan Cilograng','cby' => 35],
            ['name' => 'Pemasanan Baliho','cby' => 35],
            ['name' => 'Pemasangan  Bendera','cby' => 35],
            ['name' => 'pemasangan kamrud','cby' => 35],
            ['name' => 'Pemasangan Sepanduk','cby' => 35],
            ['name' => 'PEMBAGIA HAMPERS','cby' => 35],
            ['name' => 'Pembagian Hampers/THR','cby' => 35],
            ['name' => 'Pembagian sarung BHS','cby' => 35],
            ['name' => 'Pembagian Sarung Jalur AAW Dapil 4','cby' => 35],
            ['name' => 'Pembagian Sarung Jalur AAW Dapil 5','cby' => 35],
            ['name' => 'Pembentukan Kordes dan korcam','cby' => 35],
            ['name' => 'Pemberian Beras Jalur AAW','cby' => 35],
            ['name' => 'Pemberian Bingkisan','cby' => 35],
            ['name' => 'Pemberian sumbangan','cby' => 35],
            ['name' => 'PEMBUATAN BENDERA','cby' => 35],
            ['name' => 'Pertemuan dengan 2 jaro di davil 1','cby' => 35],
            ['name' => 'Santunan anak yatim se desa Muara','cby' => 35],
            ['name' => 'Seren Tahun Kàsepuhan Olot Omik','cby' => 35],
            ['name' => 'Silaturahmi alal bihalal dgn kepaLa desa se kecamatan Wassalam','cby' => 35],
            ['name' => 'Silaturahmi ke dulur aaw di Cihara','cby' => 35],
            ['name' => 'Silaturahmi ke dulur aaw di Pangarangan','cby' => 35],
            ['name' => 'Silaturahmi ke dulur aaw kec Cilograng','cby' => 35],
            ['name' => 'Silaturahmi ke dulur aaw kec. Bayah','cby' => 35],
            ['name' => 'Silaturahmi ke dulur aaw kec. Cibeber','cby' => 35],
        ];

        foreach ($data as $value) {
            EventCategory::create($value);
        }
    }
}
