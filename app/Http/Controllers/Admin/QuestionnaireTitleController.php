<?php

namespace App\Http\Controllers\Admin;

use App\QuestionnaireTitle;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class QuestionnaireTitleController extends Controller
{
    public function edit($id, $questionnaireId){

        $model = new QuestionnaireTitle();
        $data  = $model->editData($id);

        return view('pages.admin.questionnaire_title.edit', compact('data','questionnaireId'));
    }

    public function update(Request $request, $id){

        //validasi data
        $request->validate([
            'name' => 'required',
        ]);

        // untuk mendapatkan id akun admin yang sedang login
         $userId = auth()->guard('admin')->user()->id;
         $questionnaireId = $request->questionnaireId;


        $name = $request->name;

        $model = new QuestionnaireTitle();
        $data = $model->updateData($id,$name,$userId);

        return redirect()->route('admin-questionnaire-detail', ['id' => $questionnaireId])->with(['success' => 'Judul kuisioner telah diedit!']);
    }


    public function delete()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;

            // $sql = "DELETE FROM questionnaire_titles WHERE id=$id";
            // $data = DB::delete($sql);

            $model = new QuestionnaireTitle();
            $data = $model->deleteData($id);

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus inventori!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(){
        return view('pages.admin.questionnaire_title.create');
    }

    public function store(Request $request, $id){

        // untuk mendapatkan id akun admin yang sedang login
        $userId = auth()->guard('admin')->user()->id;

        $nama = $request->name;
        $tanggal = date('Y-m-d h:i:s');

        $model = new QuestionnaireTitle();
        $model->insertData($userId,$nama,$tanggal,$id);

        return redirect()->back()->with(['success' => 'Judul kuisioner telah ditambahkan!']);
    }

 
}
