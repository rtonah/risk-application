<?php

namespace App\Http\Controllers\Ticket\Conformite;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class GestionController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('assignedTo')->latest()->paginate(10);
        $complianceUsers = User::role('Conformité')->get();

        return view('tickets.compliance.index', compact('tickets', 'complianceUsers'));
    }

    public function filter(Request $request)
    {
        $query = $request->input('query');

        $tickets = Ticket::when($query, function ($q) use ($query) {
            $q->where('subject', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%");
        })->with('assignedTo')->latest()->paginate(10);

        $complianceUsers = User::role('Conformité')->get();

        return view('tickets.compliance.partials.table', compact('tickets', 'complianceUsers'))->render();
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        // dd($request->user_id);

        $ticket->update([
            'assigned_to' => $request->user_id,
        ]);

        return back()->with('success', 'Ticket assigné avec succès.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $ticket->status = $request->input('status');
        $ticket->save();

        return back()->with('success', 'Statut mis à jour.');
    }

}
