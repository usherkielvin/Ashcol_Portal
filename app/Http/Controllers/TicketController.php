<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Ticket::class);
        $user = $request->user();
        $query = Ticket::with(['customer', 'assignedStaff', 'status', 'comments']);

        // Filter based on user role
        if ($user->isCustomer()) {
            // Customers see only their own tickets
            $query->where('customer_id', $user->id);
        } elseif ($user->isStaff()) {
            // Staff see assigned tickets and all tickets
            $query->where(function ($q) use ($user) {
                $q->where('assigned_staff_id', $user->id)
                  ->orWhereNull('assigned_staff_id');
            });
        }
        // Admins see all tickets (no filter)

        // Filter by status if provided
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        // Filter by priority if provided
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Search by title or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(15);
        $statuses = TicketStatus::all();
        
        return view('tickets.index', [
            'tickets' => $tickets,
            'statuses' => $statuses,
            'priorities' => [
                Ticket::PRIORITY_LOW => 'Low',
                Ticket::PRIORITY_MEDIUM => 'Medium',
                Ticket::PRIORITY_HIGH => 'High',
                Ticket::PRIORITY_URGENT => 'Urgent',
            ],
        ]);
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create(): View
    {
        $this->authorize('create', Ticket::class);
        $statuses = TicketStatus::all();
        $staff = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STAFF])->get();
        
        return view('tickets.create', [
            'statuses' => $statuses,
            'staff' => $staff,
        ]);
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $this->authorize('create', Ticket::class);
        $user = $request->user();
        
        $data = $request->validated();
        
        // Customers can only create tickets for themselves
        if ($user->isCustomer()) {
            $data['customer_id'] = $user->id;
            $data['status_id'] = TicketStatus::getDefault()->id;
        }
        
        // If no staff assigned, set to null
        if (empty($data['assigned_staff_id'])) {
            $data['assigned_staff_id'] = null;
        }

        $ticket = Ticket::create($data);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);
        $ticket->load(['customer', 'assignedStaff', 'status', 'comments.user']);
        
        return view('tickets.show', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket): View
    {
        $this->authorize('update', $ticket);
        $ticket->load(['customer', 'assignedStaff', 'status']);
        $statuses = TicketStatus::all();
        $staff = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STAFF])->get();
        
        return view('tickets.edit', [
            'ticket' => $ticket,
            'statuses' => $statuses,
            'staff' => $staff,
        ]);
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);
        $data = $request->validated();
        
        // If no staff assigned, set to null
        if (empty($data['assigned_staff_id'])) {
            $data['assigned_staff_id'] = null;
        }

        $ticket->update($data);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->authorize('delete', $ticket);
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }
}

