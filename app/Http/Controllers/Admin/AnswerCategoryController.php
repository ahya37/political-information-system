<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;
use App\AnswerChoiceCategory;

class AnswerCategoryController extends Controller
{
    public function index(){
        return view("pages.admin.answer_choice_categories.index");
    }

    public function create(){
        return view('pages.admin.answer_choice_categories.create');
    }

    public function getDataAnswerCategory(Request $request){
           // DATATABLE
           $orderBy = 'name';
           switch ($request->input('order.0.column')) {
               case '3':
                   $orderBy = 'name';
                   break;
           }
   
        //    $model = new AnswerChoiceCategory();
        //    $data = $model->getData();

        $data = DB::table('answer_choice_categories')->select('id','name','created_at');
   
   
           if($request->input('search.value')!=null){
                   $data = $data->where(function($q)use($request){
                       $q->whereRaw('LOWER(name) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                   });
               }
   
            
             $recordsFiltered = $data->get()->count();
             $data = $data->orderBy($orderBy,$request->input('order.0.dir'));
             $data = $data->get();
   
             $recordsTotal = $data->count();
   
             return response()->json([
                   'draw'=>$request->input('draw'),
                   'recordsTotal'=>$recordsTotal,
                   'recordsFiltered'=>$recordsFiltered,
                   'data'=> $data
               ]);
    }

    public function store(Request $request){
        //validasi data

        $request->validate([
            'name' => 'required',
        ]);

         // untuk mendapatkan id akun admin yang sedang login
         $userId = auth()->guard('admin')->user()->id;

        //ambil data ke dalam variabel
        $name = $request->name;
        $date = date('Y-m-d h:i:s');
  


        $model = new AnswerChoiceCategory();
        $model->insertData($name,$date,$userId);

        return redirect()->route('admin-answercategory')->with(['success' => 'Data Berhasil Ditambahkan']);
    }

    public function deleteAnswerCategory(){
        DB::beginTransaction();
        try {

            $id    = request()->id;

            DB::table('answer_choice_categories')->where('id',$id)->delete();

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

    public function edit($id){

        $model = new AnswerChoiceCategory();
        $data = $model->editData($id);


        return view('pages.admin.answer_choice_categories.edit', compact('data'));
    }


    public function update(Request $request){

        //validasi data
        $request->validate([
            'name' => 'required',
        ]);

         // untuk mendapatkan id akun admin yang sedang login
         $userId = auth()->guard('admin')->user()->id;

        $id = $request->id;
        $name = $request->name;
        $date = date('Y-m-d h:i:s');

        $model = new AnswerChoiceCategory();
        $model->updateData($id,$name,$date,$userId);

        return redirect()->route('admin-answercategory')->with(['success' => 'Data Berhasil Diedit']);
    
    }
}
