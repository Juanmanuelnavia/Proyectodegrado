<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Owner;
use App\Models\Appointment;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['owner', 'user']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('owner', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();

        $totalHoy = Invoice::whereDate('created_at', today())->where('status', 'pagada')->sum('total');
        $totalMes = Invoice::whereMonth('created_at', now()->month)->where('status', 'pagada')->sum('total');

        return view('invoices.index', compact('invoices', 'totalHoy', 'totalMes'));
    }

    public function create()
    {
        $owners = Owner::orderBy('name')->get();
        $appointments = Appointment::with(['pet', 'owner'])
            ->where('status', 'completada')
            ->whereDoesntHave('invoice')
            ->orderByDesc('scheduled_at')
            ->get();
        $inventory = Inventory::where('stock', '>', 0)->orderBy('name')->get();
        return view('invoices.create', compact('owners', 'appointments', 'inventory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_id'     => 'required|exists:owners,id',
            'items'        => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }
        $tax = $subtotal * 0; // sin IVA por defecto
        $total = $subtotal + $tax;

        $invoice = Invoice::create([
            'appointment_id' => $request->appointment_id,
            'owner_id'       => $request->owner_id,
            'user_id'        => auth()->id(),
            'invoice_number' => 'FAC-' . str_pad(Invoice::count() + 1, 5, '0', STR_PAD_LEFT),
            'status'         => 'pendiente',
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'total'          => $total,
            'notes'          => $request->notes,
        ]);

        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'description' => $item['description'],
                'type'        => $item['type'] ?? 'servicio',
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total'       => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Factura creada exitosamente.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('owner', 'user', 'items', 'appointment.pet');
        return view('invoices.show', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status'         => 'required|in:pendiente,pagada,anulada',
            'payment_method' => 'required_if:status,pagada|nullable|in:efectivo,tarjeta,transferencia',
        ]);

        $invoice->update([
            'status'         => $request->status,
            'payment_method' => $request->payment_method,
            'paid_at'        => $request->status === 'pagada' ? now() : null,
        ]);

        return back()->with('success', 'Factura actualizada exitosamente.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->update(['status' => 'anulada']);
        return redirect()->route('invoices.index')
            ->with('success', 'Factura anulada exitosamente.');
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load('owner', 'user', 'items', 'appointment.pet');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('factura-' . $invoice->invoice_number . '.pdf');
    }
}