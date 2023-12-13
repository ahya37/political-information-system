<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Imports\FormManualImport;
use Maatwebsite\Excel\Facades\Excel as Excels;
use Illuminate\Support\Facades\DB;
use App\Models\District;
use App\Models\Village;
use App\Models\Province;
use App\Models\Regency;

class FormManualController extends Controller
{
    public function storeFormManual(Request $request)
    {
        try {

            $request->validate([
                'file' => 'required|mimes:xls,xlsx',
                'nik_korte' => 'required'
            ]);

            // tampung data dari excel
            $data =  Excels::toCollection(new FormManualImport, request()->file('file'));

            // export excel to collection
            $list_anggota = [];
            foreach($data as  $value){
                // $list_anggota[] = $value;
                foreach($value as $item){
                    $anggota =  DB::table('users')->select('name','nik')->where('nik', $item['nik'])->first();
                    $list_anggota[] = [
                        'is_cover' => $anggota == null ? 0 : 1,
                        'nik' => $anggota->nik ?? $item['nik'],
                        'name' => $anggota->name ?? $item['nama']
                    ];
                }
            }

            // return $list_anggota;

            // Convert the array to a JSON string before saving to Redis
            $jsonData = json_encode($list_anggota);

            // Specify the key under which you want to store the data in Redis
            // jadikan nik korte dan id admin sebagai key redis nya
            $admin_id =  auth()->guard('admin')->user()->id ?? 0;
            $redisKey = $request->nik_korte.'-'.$admin_id;

            // Save the JSON string to Redis
            Redis::del($redisKey);
            Redis::set($redisKey, $jsonData);

            $results = Redis::get($redisKey);

            $results = json_decode($results);

            // tampilkan kedalam view
            return $results;

        } catch (\Exception $e) {
           return redirect()->with(['error' => $e->getMessage()]);
        }

    }

    public function kortpsFormManual()
    {
        $regency = Regency::select('id', 'name')->where('id', 3602)->first();

        $authAdminDistrict = auth()->guard('admin')->user()->district_id;
        $districtModel  = new District();
        $district       = $districtModel->getAreaAdminKoordinator($authAdminDistrict);
        $villages  = Village::select('id','name')->where('district_id', $authAdminDistrict)->get();

        return view('pages.admin.strukturorg.rt.formmanual.index', compact('regency','district','villages'));
    }

    public function createKortpsFormManual($idx)
    {
        return $idx;
    }
}
