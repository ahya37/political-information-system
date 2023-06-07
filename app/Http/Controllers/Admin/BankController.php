<?php

namespace App\Http\Controllers\Admin;

use App\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    public function store(Request $request){

        $request->validate([
            'number' => 'required|numeric',
            'owner' => 'required',
            'bank' => 'required'
        ]);

        $bank = Bank::where('user_id', $request->userid)->first();

        if($bank) {

            #update
            $bank->update([
                'number' => $request->number,
                'owner'  => strtoupper($request->owner),
                'bank' => strtoupper($request->bank
)            ]);

        }else{

            #new
            Bank::create([
                'user_id' => $request->userid,
                'number' => $request->number,
                'owner'  => strtoupper($request->owner),
                'bank' => strtoupper($request->bank
)
            ]);
        }

        return redirect()->back()->with(['success' => 'Nomor rekening telah tersimpan']);


    }
}
