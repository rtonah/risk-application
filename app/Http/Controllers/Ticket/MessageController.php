<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use App\Models\Ticket;

class MessageController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->check() && !$request->has('anonymous') ? auth()->id() : null,
            'message' => $request->message,
        ]);

        // TODO : Notifications, mentions (@)
        return back()->with('success', 'Commentaire ajouté.');
    }

}
