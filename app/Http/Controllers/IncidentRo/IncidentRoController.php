<?php

namespace App\Http\Controllers\IncidentRo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncidentRoController extends Controller
{
    public function index()
    {
        return view('incident_ro.index');
    }

    public function create()
    {
        return view('incident_ro.create');
    }

    public function dashboard()
    {
        return view('incident_ro.ro-dashboard');
    }
}
