<?php

namespace App\Http\Controllers;

use App\Models\ResidentOut;
use Illuminate\Http\Request;

class ResidentOutController extends Controller
{
    public function index()
    {
        $residentOuts = ResidentOut::latest('exit_date')->paginate(12);
        return view('resident_outs.index', compact('residentOuts'));
    }
}
