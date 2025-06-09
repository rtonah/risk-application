<?php

namespace App\Http\Controllers\Incidence;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Incidence\ItRequestList;
use App\Models\incidence\ItRequest;
use Illuminate\Http\Request;

class IncidenceController extends Controller
{
    public function index()
    {
        return view('incidence.index');
    }

    public function create()
    {
        return view('incidence.create');
    }

    public function dashboard()
    {
        return view('incidence.it-dashboard');
    }
}
