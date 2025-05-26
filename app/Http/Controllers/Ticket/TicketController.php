<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tickets = Ticket::with('user')->latest()->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    #.. Afficher uniquement les tickets qui appartiennent à l’utilisateur connecté (authentifié),
    public function MonTicket()
    {
        $user = Auth::user();

        $tickets = Ticket::where('created_by', $user->id)->latest()->paginate(10);
        return view('tickets.me', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // max 10MB per file
        ]);

        // dd($request->has('anonymous'));

        $ticket = Ticket::create([
            'subject' => $request->title,
            'description' => $request->description,
            'is_anonymous' => $request->has('anonymous'),
            'status' => 'open',
            'created_by' => $request->has('anonymous') ? null : auth()->id(),
        ]);

        // Gestion des fichiers
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket_attachments', 'public');

                $ticket->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'uploaded_by' => $request->has('anonymous') ? null : auth()->id(),
                ]);
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket créé avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'messages.user']); // pour éviter les requêtes multiples
        return view('tickets.show', compact('ticket'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
