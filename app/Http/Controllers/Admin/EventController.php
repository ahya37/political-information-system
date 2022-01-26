<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Event;
use App\CostLess;
use App\Forecast;
use App\CostEvent;
use App\EventDetail;
use App\ForecastDesc;
use App\Models\Regency;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
                        <div class="row">
                            <div class="col-4">
                                <div class="btn-group">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="'.route('admin-event-addmember-detail', encrypt($item->id)).'">
                                                Detail
                                            </a>
                                            <a class="dropdown-item" href="'.route('admin-event-edit', $item->id).'">
                                                Edit
                                            </a>
                                            <a class="dropdown-item" href="'.route('admin-event-gallery', $item->id).'">
                                                Galeri
                                            </a>
                                            <a class="dropdown-item" href="'.route('admin-event-cost-create', $item->id).'">
                                                Tambah Biaya
                                            </a>
                                            <a class="dropdown-item" href="'.route('admin-event-addmember', $item->id).'">
                                            Tambah Peserta
                                            </a>
                                            
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-6">
                                <a class="btn btn-sm btn-danger" href="'.route('admin-event-delete', $item->id).'">
                                    <i class="fa fa-trash"></i>
                                </a>
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
                        if ($item->village == null) {
                            return 'KEC. ' .$item->district.',<br>'.''.$item->regency.'';
                        }else{
                            return 'DS.' .$item->village.',<br>'.'KEC.'.$item->district.'<br>'.''.$item->regency.'';
                        }
                    })                   
                    ->rawColumns(['action','dates','times','delete','address'])
                    ->make();
        }

        return view('pages.admin.event.index');
    }

    public function createCost($id)
    {
         $forecast = Forecast::orderBy('name','desc')->get();
        $forecast_desc = ForecastDesc::orderBy('name','desc')->get();
        return view('pages.admin.event.create-cost', compact('forecast','forecast_desc','id'));
    }
    public function create()
    {
        return view('pages.admin.event.create');
    }

    public function edit($id)
    {
        $event = Event::where('id', $id)->first();
        return view('pages.admin.event.edit', compact('event'));
    }
    public function delete($id)
    {
        $event = Event::where('id', $id)->first();
        $event->delete();

        return redirect()->back()->with(['success' => 'Event telah dihapus']);
    }

    public function addMemberEvent($id)
    {
        $event_id = $id;
        
        // mengambil member yang memiliki akun login saja, atau yg daftar mandiri
        // $memberModel = new User();
        // $members     = $memberModel->getMemberForEvent($event_id);

            // if (request()->ajax()) {
            //     return DataTables::of($members)
            //             ->addColumn('pilih', function($item) use ($event_id){
            //                 // jika event_id != id pada event, maka aktifkan kotak pilihnya
            //                 if ($item->event_id != $event_id) {
            //                     return '
            //                     <input type="checkbox" name="user_id[]" value="'.$item->user_id.'" class="form-control-sm">
            //                     <input type="hidden" name="event_id" value="'.$event_id.'" >
            //                     ';
            //                 }
            //                 return '
            //                     <span class="fa fa-check"></span>
            //                 ';
            //             })
            //             ->addColumn('district', function($item){
            //                 return $item->village;
            //             })
            //              ->addColumn('regency', function($item){
            //                 return $item->district;
            //             })
            //             ->rawColumns(['pilih','district','regency'])
            //             ->make();

            //         }
        // $regencyModel = new Regency();
        // $regencies     = $regencyModel->getSelectRegencies();
        $provinceModel = new Province();
        $province = $provinceModel->getDataProvince();
        return view('pages.admin.event.add-participant', compact('province','event_id'));
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

    public function storeAddMemberEventAjax()
    {
        $user_id = request()->userId;
        $token   = request()->_token;
        $even_Id   = decrypt(request()->eventId);
        $eventDetailModel = new EventDetail();

        if ($token != null) {

            $memberOfEvent = $eventDetailModel::where('user_id', $user_id)
                                                ->where('event_id',$even_Id)->count();
            if ($memberOfEvent > 0) {

                $success = false;
                $message = "Sudah terdaftar";

                return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
            }else{

                $event =  EventDetail::create([
                    'user_id' => $user_id,
                    'event_id' => $even_Id
                ]);
                
                if ($event) {
                    $success = true;
                    $message = "Berhasil menambahkan";
    
                }else{
                    $success = false;
                    $message = "Gagal menambahkan";
                }
                return response()->json([
                    'success' => $success,
                    'message' => $message,
                ]);
            }

        }
        
    }

    public function storeAddParticipant($event_id, $user_id)
    {
        $user = User::select('name')->where('id', $user_id)->first();

        $eventDetail = new EventDetail();
            $eventDetail::create([
                'event_id' => $event_id,
                'participant' => strtoupper($user->name)
            ]);

        return redirect()->back()->with(['success' => 'Berhasil menambahkan peserta']);

    }

    public function storeAddParticipantOther(Request $request, $event_id)
    {

        $eventDetail = new EventDetail();
            $eventDetail::create([
                'event_id' => $event_id,
                'participant' => strtoupper($request->name)
            ]);

        return redirect()->back()->with(['success' => 'Berhasil menambahkan peserta']);

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        Event::create([
            'title' => $request->title,
            'description' => $request->desc,
            'time' => date('H:i', strtotime($request->time)),
            'date' => date('Y-m-d', strtotime($request->date)),
            'regency_id' => $request->regency_id,
            'dapil_id' => $request->dapil_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id
        ]);

        return redirect()->route('admin-event')->with(['success' => 'Event baru telah dibuat']);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'desc' => 'required',
        ]);

        $event = Event::where('id', $id)->first();
        $event->update([
            'title' => $request->title,
            'description' => $request->desc,
            'time' => date('H:i', strtotime($request->time)),
            'date' => date('Y-m-d', strtotime($request->date)),
            'regency_id' => $request->regency_id == null ? $event->regency_id : $request->regency_id,
            'dapil_id' => $request->dapil_id == null ? $event->dapil_id : $request->dapil_id,
            'district_id' => $request->district_id == null ? $event->district_id : $request->district_id,
            'village_id' => $request->village_id == null ? $event->village_id : $request->village_id
        ]);

        return redirect()->route('admin-event')->with(['success' => 'Event telah diubah']);
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

    public function costEventStore(Request $request, $id)
    {
        $request->validate([
            'file' => 'nullable|mimes:jpeg,jpg,png,pdf'
        ]);

        if ($request->hasFile('file')) {
                $fileImage = $request->file->store('assets/cost/event','public');

                // simpan juga ke directory cost politik
                $fileImage = $request->file->store('assets/cost/','public');
            }else{
                $fileImage = 'NULL';
            }

        // simpan ke cost event
            // id, event_id, nominal, file
        CostEvent::create([
            'event_id' => $id,
            'nominal' => $request->nominal,
            'file' => $fileImage,
        ]);

        $eventModel = new Event();
        $event = $eventModel->getAddressEvent($id);

        $address = $event->village != null ? 'DS. ' .$event->village. ', KEC.'.$event->district. ', '.$event->regency : 'KEC. ' .$event->district. ', '.$event->regency;
        

        // simpan ke cost lest untuk pengeluaran polotik
            // 
        CostLess::create([
            'date' => date('Y-m-d'),
            'forcest_id' => $request->forecast_id,
            'forecast_desc_id' => $request->forecast_desc_id,
            'received_name' => $request->received_name,
            'address' => $address,
            'nominal' => $request->nominal,
            'file' => $fileImage,
        ]);

        return redirect()->route('admin-event')->with(['success' => 'Biaya event telah tersimpan']);
    }

    
}
