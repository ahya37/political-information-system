<?php

namespace App\Http\Controllers\Admin;

use File;
use App\Event;
use App\EventGallery;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EventGalleryController extends Controller
{
    public function index($id)
    {
        $id = $id;
        $event = Event::select('id','title')->where('id', $id)->first();
        return view('pages.admin.gallery.index', compact('event'));
    }

    public function store(Request $request, $id)
    {
        
        $this->validate($request, [
               'file' => 'required|mimes:png,jpg,jpeg',
           ]);

        
        $file = $request->file('file')->store('assets/user/galleries','public');

        EventGallery::create([
            'event_id' => $id,
            'title' => $request->title,
            'descr' => $request->desc,
            'file'  => $file,
            'file_type'  => 'image',
            'cby'   => auth()->guard('admin')->user()->id
        ]);

        return redirect()->back()->with(['success' => 'Galeri telah ditambahkan']);

    }
	
	public function upodateFoto(Request $request, $id)
    {
        
        $this->validate($request, [
               'file' => 'mimes:png,jpg,jpeg',
        ]);
		
		#get file by id
		$gallery = EventGallery::where('id', $id)->first();
		
		#jika ada file baru, maka update
		if($request->file == null){
			
			$file = $gallery->file;
			
		}else{
			
			$file = $request->file('file')->store('assets/user/galleries','public');
			#hapus file lama
			$dir_file = storage_path('app').'/public/'.$gallery->file;
            if (file_exists($dir_file)) {
                File::delete($dir_file);
            }
			
		}
		
		#update di db
		$gallery->update([
				'title' => $request->title,
				'descr' => $request->desc,
				'file'  => $file,
				'file_type'  => 'image',
				'cby'   => auth()->guard('admin')->user()->id
		]);

       
        return redirect()->back()->with(['success' => 'Galeri telah diubah!']);

    }

    public function storeVideo(Request $request, $id)
    {
        
        $this->validate($request, [
               'file' => 'required|file|mimetypes:video/mp4',
           ]);

        
        $file = $request->file('file')->store('assets/user/galleries/video','public');

        EventGallery::create([
            'event_id' => $id,
            'title' => $request->title,
            'descr' => $request->desc,
            'file'  => $file,
            'file_type'  => 'video',
            'cby'   => auth()->guard('admin')->user()->id
        ]);

        return redirect()->back()->with(['success' => 'Galeri telah ditambahkan']);

    }

    public function detailEventGallery($id)
    {
        $event_gallery = EventGallery::where('id', $id)->first();
        return view('pages.admin.gallery.detail', compact('event_gallery'));
    }

    public function deleteGallery(){

        DB::beginTransaction();
        try {

            $id    = request()->id;

            #hapus gallery
            EventGallery::where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil hapus galleri!'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something when wrong!',
                'error' => $e->getMessage()
            ]);
        }
	}

}
