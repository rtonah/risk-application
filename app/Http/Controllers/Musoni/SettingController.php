<?php

namespace App\Http\Controllers\Musoni;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CbsCredential;

class SettingController extends Controller
{
    public function index()
    {
        $credentials = CbsCredential::all();
        return view('musoni.settings.index', compact('credentials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'login' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        CbsCredential::create([
            'name' => $request->name,
            'login' => $request->login,
            'password' => $request->password,
        ]);

        return redirect()->route('setting.index')->with('success', 'Identifiants CBS ajoutés avec succès.');
    }
}

