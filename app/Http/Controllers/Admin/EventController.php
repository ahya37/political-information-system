<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\EventDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    public function index()
    {
        $eventModel = new Event();
        $events     = $eventModel->getEvents();
        if (request()->ajax()) {
            return DataTables::of($events)
                    ->addColumn('action', function($item){
                        return '
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">Aksi</button>
                                <div class="dropdown-menu">
                                     <a class="dropdown-item" href="'.route('admin-event-addmember-detail', encrypt($item->id)).'">
                                        Detail
                                     </a>
                                      <a class="dropdown-item" href="'.route('admin-event-addmember', encrypt($item->id)).'">
                                        Tambah Peserta
                                     </a>
                                     <a class="dropdown-item" href="'.route('admin-event-gallery', encrypt($item->id)).'">
                                        Galeri
                                     </a>
                                </div>
                            </div>
                        </div>
                        ';
                    })->addColumn('absen', function($item){
                        return $item->present.'/'.$item->invitation;
                    })->addColumn('dates', function($item){
                        return date('d-m-Y', strtotime($item->date));
                    })->addColumn('times', function($item){
                        return date('H:i', strtotime($item->time));
                    })
                    ->rawColumns(['action','absen','dates','times'])
                    ->make();
        }

        return view('pages.admin.event.index');
    }

    public function create()
    {
        return view('pages.admin.event.create');
    }

    public function addMemberEvent($id)
    {
        $event_id = decrypt($id);
        
        // mengambil member yang memiliki akun login saja, atau yg daftar mandiri
        $memberModel = new User();
        $members     = $memberModel->getMemberForEvent($event_id);

            if (request()->ajax()) {
                return DataTables::of($members)
                        ->addColumn('pilih', function($item) use ($event_id){
                            return '
                            <input type="checkbox" name="user_id[]" value="'.$item->user_id.'" class="form-control-sm">
                            <input type="hidden" name="event_id" value="'.$event_id.'" >
                            ';
                        })
                        ->addColumn('district', function($item){
                            return $item->village;
                        })
                         ->addColumn('regency', function($item){
                            return $item->district;
                        })
                        ->rawColumns(['pilih','district','regency'])
                        ->make();

                    }
        return view('pages.admin.event.add-member');
    }

    public function storeAddMemberEvent(Request $request)
    {
        $user_id = $request->user_id;
        foreach ($user_id as $key => $value) {
            $eventDetail = new EventDetail();
            $eventDetail->user_id  = $value;
            $eventDetail->event_id = $request->event_id;
            $eventDetail->save();
        }

        return redirect()->route('admin-event')->with(['success' => 'Anggota telah ditambahkan']);
        
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'desc' => 'required',
            'address' => 'required'
        ]);

        Event::create([
            'title' => $request->title,
            'description' => $request->desc,
            'address' => $request->address,
            'time' => date('H:i', strtotime($request->time)),
            'date' => date('Y-m-d', strtotime($request->date)),
        ]);

        return redirect()->route('admin-event')->with(['success' => 'Event baru telah dibuat']);
    }

    public function evenDetials($id)
    {
        $event_id = decrypt($id);
        $evenDetailModel = new EventDetail();
        $event_detail     = $evenDetailModel->getEventDetail($event_id);
        if (request()->ajax()) {
                return DataTables::of($event_detail)
                        ->addColumn('present', function($item){
                            if ($item->log_present === NULL) {
                                return '<span class="badge badge-danger">Tidak</span>';
                            }else{
                                return '<span class="badge badge-success">Ya</span>';
                            }
                        })
                        ->addColumn('log_presents', function($item){
                            if ($item->log_present != NULL) {
                                return date('d-m-Y H:i:s', strtotime($item->log_present));
                            }else{
                                return '-';
                            }
                        })
                        ->rawColumns(['present','log_presents'])
                        ->make();
                    }

        $title = '';
        foreach ($event_detail as $val) {
            $title = $val->title;
        }

        return view('pages.admin.event.detail', compact('title'));
    }

    public function galleryEvent($id)
    {
        return view('pages.admin.event.gallery');
    }
}
