<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Destination;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $destination = Destination::all();
        $tickets = Ticket::query();

        if ($request->has('search')) {
            $tickets->where('name', 'like', '%' . $request->search . '%');
        }

        $tickets = $tickets->get();

        return view('admin.ticket.index_ticket', compact('tickets', 'destination'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name_ticket' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'rules' => 'required|string',
                'open' => 'required|string',
                'close' => 'required|string',
                'type' => 'required|string',
                'destination_id' => 'required',

            ]);
            Ticket::create([
                'name_ticket' => $request->name_ticket,
                'price' => $request->price,
                'rules' => $request->rules,
                'open' => $request->open,
                'close' => $request->close,
                'type' => $request->type,
                'status' => 'Active',
                'is_active' => $request->is_active,
                'destination_id' => $request->destination_id,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('ticket.index')->with('success', 'Tiket berhasil ditambahkan.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        try {
            $request->validate([
                'name_ticket' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'rules' => 'required|string',
                'open' => 'required|string',
                'close' => 'required|string',
                'type' => 'required|string',
                'status' => 'required|string',
                'destination_id' => 'required',
            ]);
            $ticket = Ticket::find($id);
            $ticket->update($request->all());
            return redirect()->route('ticket.index')->with('success', 'Tiket berhasil diubah.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Show checkout page for a ticket
     */
    public function checkout(string $id)
    {
        $ticket = Ticket::with('destination')->findOrFail($id);
        return view('payment.checkout', compact('ticket'));
    }
}
