<?php

namespace App\Http\Controllers\API;

use App\EventGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventGalleryController extends Controller
{
    public function getDataEventGalleries($eventId)
    {
        $event_galeries = EventGallery::select('id','file')->where('event_id', $eventId)
                        ->orderBy('id','desc')->get();
        return response()->json([
            'success' => true,
            'data' => $event_galeries
        ], 200);
    }
}
