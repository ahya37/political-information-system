<?php

use Illuminate\Database\Seeder;
use App\Job;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $data = [
            ['name' => 'Belum/Tidak Bekerja'],
            ['name' => 'Swasta'],
            ['name' => 'PNS'],
            ['name' => 'TNI'],
            ['name' => 'Polri'],
            ['name' => 'Pengusaha'],
            ['name' => 'Petani'],
            ['name' => 'Nelayan'],
            ['name' => 'RT'],
            ['name' => 'Wiraswasta'],
            ['name' => 'PELAJAR'],
            ['name' => 'Pensiunan'],
            ['name' => 'Perdagangan'],
            ['name' => 'Petani / Pekebun'],
            ['name' => 'Peternak'],
            ['name' => 'Nelayan / Perikanan'],
            ['name' => 'Industri'],
            ['name' => 'Konstruksi'],
            ['name' => 'Transportasi'],
            ['name' => 'Karyawan BUMN'],
            ['name' => 'Karyawan BUMD'],
            ['name' => 'Karyawan Honorer'],
            ['name' => 'Buruh Harian Lepas'],
            ['name' => 'Buruh Tani / Perkebunan'],
            ['name' => 'Buruh Nelayan / Perikanan'],
            ['name' => 'Buruh Peternakan'],
            ['name' => 'Pembantu Rumah Tangga'],
            ['name' => 'Tukang Cukur'],
            ['name' => 'Tukang Listrik'],
            ['name' => 'Tukang Batu'],
            ['name' => 'Tukang Kayu'],
            ['name' => 'Tukang Sol Sepatu'],
            ['name' => 'Tukang Las / Pandai Besi'],
            ['name' => 'Tukang Jahit'],
            ['name' => 'Penata Rambut'],
            ['name' => 'Penata Rias'],
            ['name' => 'Penata Busana'],
            ['name' => 'Mekanik'],
            ['name' => 'Tukang Gigi'],
            ['name' => 'Seniman'],
            ['name' => 'Tabib'],
            ['name' => 'Paraji'],
            ['name' => 'Perancang Busana'],
            ['name' => 'Penterjemah'],
            ['name' => 'Imam Masjid'],
            ['name' => 'Pendeta'],
            ['name' => 'Pastur'],
            ['name' => 'Wartawan'],
            ['name' => 'Ustadz / Mubaligh'],
            ['name' => 'Juru Masak'],
            ['name' => 'Promotor Acara'],
            ['name' => 'Anggota DPR-RI'],
            ['name' => 'Anggota DPD'],
            ['name' => 'Anggota BPK'],
            ['name' => 'Anggota Mahkamah Konstitusi'],
            ['name' => 'Anggota Kabinet / Kementerian'],
            ['name' => 'Duta Besar'],
            ['name' => 'Gubernur'],
            ['name' => 'Wakil Gubernur'],
            ['name' => 'Wakil Bupati'],
            ['name' => 'Walikota'],
            ['name' => 'Wakil Walikota'],
            ['name' => 'nggota DPRD Propinsi'],
            ['name' => 'Anggota DPRD Kabupaten / Kota'],
            ['name' => 'Dosen'],
            ['name' => 'Guru'],
            ['name' => 'Pilot'],
            ['name' => 'Pengacara'],
            ['name' => 'Notaris'],
            ['name' => 'Arsitek'],
            ['name' => 'Akuntan'],
            ['name' => 'Konsultan'],
            ['name' => 'Dokter'],
            ['name' => 'Bidan'],
            ['name' => 'Perawat'],
            ['name' => 'Apoteker'],
            ['name' => 'Psikiater / Psikolog'],
            ['name' => 'Penyiar Televisi'],
            ['name' => 'Penyiar Radio'],
            ['name' => 'Pelaut'],
            ['name' => 'Peneliti'],
            ['name' => 'Sopir'],
            ['name' => 'Pialang'],
            ['name' => 'Paranormal'],
            ['name' => 'Pedagang'],
            ['name' => 'Perangkat Desa'],
            ['name' => 'Kepala Desa'],
            ['name' => 'Biarawati'],
            ['name' => 'LAIN-LAIN']
        ];

        foreach($data as $row){
            Job::create($row);
        }

    }
}
