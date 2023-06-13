<?php

namespace App\Http\Controllers\Admin;

use App\FamilyGroup;
use App\Models\Regency;
use App\DetailFamilyGroup;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FamilyGroupController extends Controller
{
    protected $FamilyGroupModel;
    protected $DetailFamilyGroupModel;

    public function __construct()
    {
        $this->FamilyGroupModel       = new FamilyGroup();
        $this->DetailFamilyGroupModel = new DetailFamilyGroup();
    }
    public function index(){

        $familyGroups      = $this->FamilyGroupModel->getDataFamilyGroups();

        $no = 1;

        return view('pages.admin.familygroup.index', compact('familyGroups','no'));

    }

    public function create(){

        $regency = Regency::select('id')->where('id', 3602)->first();

        return view('pages.admin.familygroup.create', compact('regency'));

    }

    public function storeGroupLeader(Request $request){

       try {

        $validated = $request->validate([
            'member' => 'required|string'
        ]);


        #cek, jika user_id sudah ada
        $chekUserId = $this->FamilyGroupModel->select('user_id')->where('user_id', $request->member)->count();
        if ($chekUserId > 0) return redirect()->back()->with(['error' => 'Gagal menyimpan, anggota sudah menjadi kepala kelompok keluarga!']);

        $this->FamilyGroupModel->create([
            'user_id' => $request->member,
            'notes'   => $request->notes,
            'cby'     => auth()->guard('admin')->user()->id
        ]);

        return redirect()->route('admin-familygroup')->with(['success' => 'Kepala kelompok kelurga telah ditambahkan!']);

       } catch (\Exception $e) {
        
            return $e->getMessage();
       }

    }

    public function memberOfFamilygroup($id){

        $headFamilyGroup        = $this->FamilyGroupModel->getDataFamilyGroup($id);

        $detailFamilyGroup      = $this->DetailFamilyGroupModel->getMemberByFamilyGroupId($id);

        $regency = Regency::select('id')->where('id', 3602)->first();

        $no = 1;

        return view('pages.admin.familygroup.listmember', compact('detailFamilyGroup','no','headFamilyGroup','regency'));


    }

    public function storeMemberFamilyGroup(Request $request, $id){

        try {

            $validated = $request->validate([
                'member' => 'required|string'
            ]);
    
            // #cek, jika user_id sudah ada
            $chekUserId = $this->DetailFamilyGroupModel->select('user_id')->where('user_id', $request->member)->count();
            if ($chekUserId > 0) return redirect()->back()->with(['error' => 'Gagal menyimpan, anggota sudah menjadi kepala kelompok keluarga!']);
    
            $this->DetailFamilyGroupModel->create([
                'family_group_id' => $id,
                'user_id' => $request->member,
                'notes'   => $request->notes,
                'cby'     => auth()->guard('admin')->user()->id
            ]);
    
            return redirect()->back()->with(['success' => 'Anggota kelurga serumah telah ditambahkan!']);
    
           } catch (\Exception $e) {
            
                return $e->getMessage();
           }
    }

    public function delete()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;

            $this->DetailFamilyGroupModel->where('family_group_id', $id)->delete(); #delete family groupnya / keluarga serumah
            $this->FamilyGroupModel->where('id', $id)->delete(); #delete anggota family group ny / keluarga serumah

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus keluarga serumah!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function deleteMember()
    {

        DB::beginTransaction();
        try {

            $id    = request()->id;

            $this->DetailFamilyGroupModel->where('id', $id)->delete(); #delete family groupnya / keluarga serumah

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil hapus keluarga serumah!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function editGroupLeader($id){

        $regency     = Regency::select('id')->where('id', 3602)->first();

        $familyGroup = $this->FamilyGroupModel->with(['user'])->where('id', $id)->first();

        return view('pages.admin.familygroup.edit', compact('regency','familyGroup'));

    }

    public function updateGroupLeader(Request $request, $id){

        try {
 
         $validated = $request->validate([
             'member' => 'required|string'
         ]);

         $familyGroup = $this->FamilyGroupModel->select('user_id')->where('id', $id)->first();
 
         #cek, jika user_id sudah ada, tapi selain dirinya
         $chekUserId = $this->FamilyGroupModel->select('user_id')
                        ->where('user_id', $request->member)
                        ->where('user_id','!=', $familyGroup->user_id)
                        ->count();

         if ($chekUserId > 0) return redirect()->back()->with(['error' => 'Gagal menyimpan, anggota sudah menjadi kepala kelompok keluarga!']);

         $this->FamilyGroupModel->where('id', $id)->update([
             'user_id' => $request->member,
             'notes'   => $request->notes,
             'cby'     => auth()->guard('admin')->user()->id,
         ]);
 
         return redirect()->route('admin-familygroup')->with(['success' => 'Kepala kelompok kelurga telah ditambahkan!']);
 
        } catch (\Exception $e) {
         
             return $e->getMessage();
        }
 
     }
}
