<?php

namespace App\Http\Controllers;

use App\AbsenEvent;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\ResponseFormatter;
use DB;
use App\EventGallery;

class EventController extends Controller
{
    public function index()
    {
       $user_id = Auth::user()->id;

       
        // menampilkan event yang dia ikuti
        $eventModel = new Event();

        $events     = $eventModel->getEventByMember($user_id);
        if (request()->ajax()) {
            return DataTables::of($events)
                    ->addColumn('action', function($item){
                        return '
                        <div class="row">
                            <div class="col-4">
                                <div class="btn-group">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="'.route('admin-event-edit', $item->id).'">
                                                Edit
                                            </a>
                                            <a class="dropdown-item" href="'.route('member-event-gallery', $item->id).'">
                                                Galeri
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-6">
                                <button class="btn btn-sm btn-danger" onClick="onDelete(this)" value="'.$item->title.'" id="'.$item->id.'">
                                    <i class="fa fa-trash"></i>
                                </button>
                                </div>
                        </div>
                        ';
                    })->addColumn('dates', function($item){
                        return date('d-m-Y', strtotime($item->date));
                    })->addColumn('times', function($item){
                        return date('H:i', strtotime($item->time));
                    })->addColumn('delete', function($item){
                        return ' <a class="dropdown-item" href="'.route('admin-event-delete', $item->id).'">
                                        Hapus
                                </a>';
                    })
                    ->addColumn('address', function($item){
                        if ($item->district == null) {
                            return $item->regency;
                        }elseif($item->village == null){
                            return 'KEC. ' .$item->district.',<br>'.''.$item->regency.'';
                        }
                        else{
                            return 'DS.' .$item->village.',<br>'.'KEC.'.$item->district.'<br>'.''.$item->regency.'';
                        }
                    })                   
                    ->rawColumns(['action','dates','times','delete','address'])
                    ->make();
        }

        return view('pages.event.index');
    }

    public function create()
    {
        return view('pages.event.create');
    }

    public function store(Request $request)
    {
        
        $this->validate($request, [
            'title' => 'required',
        ]);

        $user_id = Auth::user()->id;

        Event::create([
            'title' => $request->title,
            'description' => $request->desc,
            'time' => date('H:i', strtotime($request->time)),
            'date' => date('Y-m-d', strtotime($request->date)),
            'regency_id' => $request->regency_id,
            'dapil_id' => $request->dapil_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id,
            'cby' => $user_id
        ]);

        return redirect()->route('member-event')->with(['success' => 'Event baru telah dibuat']);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {

            $id = $request->id;
            
            // cek ke aktivitas umrah apakah id ini masih active
            $event = Event::where('id', $id)->first();
            $event->update(['isdelete' => 1]);

            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus event!'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function storeAbsen($event_detail_id)
    {
        $event_detail_ids = decrypt($event_detail_id);
        $absen = AbsenEvent::create([
            'event_detail_id' => $event_detail_ids,
            'user_id' => Auth::user()->id
        ]);

        return redirect()->back()->with(['success' => 'Absen berhasil']);
    }

    public  function gallery($id){
        
        $event = Event::select('id','title')->where('id', $id)->first();
        return view('pages.gallery.index', compact('event'));

    }

    public function storeGallery(Request $request, $id)
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
            'cby'   => Auth::user()->id
        ]);

        return redirect()->back()->with(['success' => 'Galeri telah ditambahkan']);

    }

    public function detailEventGallery($id)
    {
        
        $event_gallery = EventGallery::where('id', $id)->first();
        return view('pages.gallery.detail', compact('event_gallery'));
    }

    public function edit(){

        return 'edit';
    }

}
