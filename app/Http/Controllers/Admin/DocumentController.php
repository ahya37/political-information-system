<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocumentController extends Controller
{
    public function downloadFormatFormKortps()
    {
        $file = public_path('/docs/util/format-upload-form.xlsx');
        $headers = array(
            'Content-Type:aplication/pdf',
        );

        return response()->download($file, 'format-upload-form', $headers);
    }
}
