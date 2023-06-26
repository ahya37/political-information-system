<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\QuestionnaireTitle;
use App\Helpers\ResponseFormatter;

class QuestionnaireTitleController extends Controller
{
    public function edit($id){

        $model = new QuestionnaireTitle();
        $data = $model->editData($id);

        return view('pages.admin.questionnaire_title.edit', compact('data'));
    }

    public function update(Request $request){

        //validasi data
        $request->validate([
            'name' => 'required',
        ]);


        $name = $request->name;
        $id = $request->id;

        $model = new QuestionnaireTitle();
        $data = $model->updateData($id,$name);

        return redirect()->route('admin-questionnaire');
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
}
