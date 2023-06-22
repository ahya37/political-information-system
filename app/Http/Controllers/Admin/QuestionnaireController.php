<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionnaireController extends Controller
{
    public function index(){

        return view('pages.admin.questionnaires.index');
    }

    public function create(){


    }
}
