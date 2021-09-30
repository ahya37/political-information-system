<?php

namespace App\Http\Controllers\API;

use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function getAdmin()
    {
        $adminModel = new Admin();
        $admins    = $adminModel->getAdmins();
        return response()->json([
            'success' => true,
            'data' => $admins
        ]);
    }
}
