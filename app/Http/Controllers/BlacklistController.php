<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blacklist;
use Illuminate\Support\Facades\Auth;

class BlacklistController extends Controller
{
    public function index(Request $request) {
        $query = Blacklist::query();

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $blacklists = $query->latest()->paginate(10); // or whatever you're using

        return view('blacklists.index', compact('blacklists'));
    }

    public function search() {

        return view('blacklists.search');
    }

    public function create() {
        return view('blacklists.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'national_id' => 'integer|unique:blacklists,national_id',
            'national_id' => ['required', 'regex:/^\d{3} \d{3} \d{3} \d{3}$/'],
            'reason' => 'required|string',
            'document' => 'nullable|file|mimes:pdf',
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents', 'public');
            $validated['document_path'] = $path;
        }

        $validated['created_by'] = Auth::id();
        Blacklist::create($validated);

        return redirect()->route('blacklists.index')->with('success', 'Client blacklisted successfully.');
    }

    public function unblock($id) {
        $bl = Blacklist::findOrFail($id);
        $bl->update([
            'status' => '2',
            'unblocked_by' => Auth::id(),
            'unblocked_at' => now()
        ]);

        return redirect()->route('blacklists.index')->with('success', 'Client unblocked.');
    }
    
    public function filter(Request $request)
    {
        $query = $request->input('query');

        $blacklists = Blacklist::when($query, function ($q) use ($query) {
            $q->where('national_id', 'like', '%' . $query . '%')
            ->orWhere('full_name', 'like', '%' . $query . '%'); // Optional
        })->orderBy('created_at', 'desc')->get();

        return view('blacklists.partials.table', compact('blacklists'));
    }


}


