<?php

namespace App\Http\Controllers;

use App\AbsenEvent;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

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
                        if ($item->created_at == NULL) {
                            return '
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">Aksi</button>
                                    <div class="dropdown-menu">
                                         <a class="dropdown-item" href="'.route('member-event-absen', encrypt($item->event_detail_id)).'">
                                            Absensi
                                         </a>
                                    </div>
                                </div>
                            </div>
                            ';
                        }else{
                            return '-';
                        }
                    })
                    ->addColumn('present', function($item){
                            if ($item->created_at === NULL) {
                                return '<span class="badge badge-danger">Tidak</span>';
                            }else{
                                return '<span class="badge badge-success">Ya</span>';
                            }
                    })
                    ->addColumn('dates', function($item){
                        return date('d-m-Y', strtotime($item->date));
                    })
                    ->addColumn('times', function($item){
                        return date('H:i', strtotime($item->time));
                    })
                    ->rawColumns(['action','dates','times','present'])
                    ->make();
        }
        return view('pages.event.index');
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

}
