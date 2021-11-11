<?php

namespace App\Http\Controllers\Admin;

use App\Caleg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalegController extends Controller
{
    public function create($dapil_id)
    {
        return view('pages.admin.caleg.create', compact('dapil_id'));
    }

    public function save(Request $request, $dapil_id)
    {
       $this->validate($request, [
           'id' => 'required'
       ]);

       // cek jika anggota sudah terdaftar pada data celeg
       $calegModel = new Caleg();

       $caleg = $calegModel->where('user_id', $request->id)->count();
       if ($caleg > 0) {
            return redirect()->back()->with(['error' => 'Caleg telah terdaftar']);
       }

       $calegModel->create([
           'dapil_id' => $dapil_id,
           'user_id' => $request->id,
        ]);

        // registrasikan menu akses

        return redirect()->route('admin-dapil-detail', ['id' => $dapil_id])->with(['success' => 'Caleg telah ditambahkan']);
    }
    
}
