<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\EventGallery;
use Illuminate\Http\Request;
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

}
