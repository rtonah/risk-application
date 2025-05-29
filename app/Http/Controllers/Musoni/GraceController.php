<?php

namespace App\Http\Controllers\Musoni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GraceController extends Controller
{
    public function index(Request $request) {
        return view('musoni.grace.index');
    }
}
