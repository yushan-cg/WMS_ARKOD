<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return view('backend.invoice.invoice_list', compact('invoices'));
    }

    public function create()
    {
        return view('backend.invoice.invoice_form');
    }

    public function searchCustomer(Request $request)
    {
        $query = $request->get('query');

        $customers = DB::table('clients')
            ->select('name', 'address', 'attention', 'tel')
            ->where('name', 'like', "$query%")
            ->union(DB::table('customers')
                ->select('name', 'address', 'attention', 'tel')
                ->where('name', 'like', "$query%"))
            ->get();

        $html = '';
        foreach ($customers as $customer) {
            $html .= '<div class="dropdown-item" data-details=\'' . json_encode($customer) . '\'>' . $customer->name . '</div>';
        }

        return response()->json($html);
    }

    public function store(Request $request)
{
    DB::beginTransaction();

    try {
        $request->validate([
            'customer_id' => 'required|string',
            'sst_percentage' => 'required|numeric',
            'payment_method' => 'required|string',
            'name' => 'required|string',
            'address' => 'required|string',
            'attention' => 'required|string',
            'tel' => 'required|string',
            'payment_terms' => 'nullable|string',
            'due_date' => 'nullable|date',
            'items' => 'nullable|array',
            'items.*.quantity' => 'nullable|integer',
            'items.*.description' => 'nullable|string',
            'items.*.unit_price' => 'nullable|numeric',
        ]);

        $dateNow = Carbon::now();  // Current date and time
        $dateInv = $dateNow->format('dmy');  // Format for invoice number
        // Format current date for database storage
        $formattedDate = $dateNow->format('Y-m-d');

        // Create and save the invoice
        $invoice = new Invoice();
        $invoice->customer_id = $request->input('customer_id');
        $invoice->date = $formattedDate;
        $invoice->invoice_no = 'ARKODSI-' . $dateInv . '-' . $request->customer_id;
        $invoice->payment_method = $request->input('payment_method');
        $invoice->name = $request->input('name');
        $invoice->address = $request->input('address');
        $invoice->attention = $request->input('attention');
        $invoice->tel = $request->input('tel');
        $invoice->payment_terms = $request->input('payment_terms');
        $invoice->due_date = $request->input('due_date');
        $invoice->save();

        $subtotalPrice = 0;
        $finalItems = [];

        // Process and save invoice items
        $items = $request->input('items');
        if ($items) {
            foreach ($items as $itemData) {
                $quantity = isset($itemData['quantity']) && $itemData['quantity'] !== '' ? $itemData['quantity'] : null;
                $unitPrice = isset($itemData['unit_price']) && $itemData['unit_price'] !== '' ? $itemData['unit_price'] : null;
                $totalPrice = $quantity !== null && $unitPrice !== null ? $quantity * $unitPrice : null;

                if ($totalPrice !== null) {
                    $subtotalPrice += $totalPrice;
                }

                // Format prices to 2 decimal places
                $unitPrice = $unitPrice !== null ? number_format($unitPrice, 2, '.', '') : null;
                $totalPrice = $totalPrice !== null ? number_format($totalPrice, 2, '.', '') : null;

                $item = new InvoiceItem();
                $item->invoice_id = $invoice->id;  // Assign the invoice ID to the item
                $item->quantity = $quantity;
                $item->description = $itemData['description'] ?? '';
                $item->unit_price = $unitPrice;
                $item->total_price = $totalPrice;

                $finalItems[] = $item;
            }
        }

        // Save each item to the database
        foreach ($finalItems as $item) {
            $item->save();
        }

        // Calculate and save financial details
        $subtotalPrice = number_format($subtotalPrice, 2, '.', '');
        $sstPercentage = $request->input('sst_percentage');
        $sst = ($sstPercentage / 100) * $subtotalPrice;
        $sst = number_format($sst, 2, '.', '');
        $final_price = $subtotalPrice + $sst;
        $final_price = number_format($final_price, 2, '.', '');

        $invoice->subtotal = $subtotalPrice;
        $invoice->sstPercentage = $sstPercentage;
        $invoice->sst = $sst;
        $invoice->final_price = $final_price;
        $invoice->save();  // Save the updated invoice

        DB::commit();

        // Data for PDF
        $data = [
            'customer_id' => $invoice->customer_id,
            'invoice_no' => $invoice->invoice_no,
            'date' => Carbon::parse($invoice->date)->format('d-m-y'),
            'payment_method' => $invoice->payment_method,
            'name' => $invoice->name,
            'address' => $invoice->address,
            'attention' => $invoice->attention,
            'tel' => $invoice->tel,
            'payment_terms' => $invoice->payment_terms,
            'due_date' => Carbon::parse($invoice->due_date)->format('d-m-y'),
            'items' => $finalItems,
            'subtotal' => $subtotalPrice,
            'sstPercentage' => $sstPercentage,
            'sst' => $sst,
            'final_price' => $final_price,
        ];

        $pdf = PDF::loadView('backend.invoice.invoice', $data);

        $filename = $invoice->invoice_no . '.pdf';
        $filepath = public_path('pdfs/' . $filename);
        $pdf->save($filepath);

        return response()->json([
            'pdf_url' => url('pdfs/' . $filename),
            'redirect_url' => route('invoices.index')
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}




    public function show($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $pdf = PDF::loadView('invoices.show', compact('invoice'));
        return $pdf->stream('invoice.pdf');
    }

    public function generatePdf($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        $data = [
            'customer_id' => $invoice->customer_id,
            'invoice_no' => $invoice->invoice_no,
            'date' => Carbon::parse($invoice->date)->format('d-m-y'), // Use formatted date here
            'name' => $invoice->name,
            'address' => $invoice->address,
            'attention' => $invoice->attention,
            'tel' => $invoice->tel,
            'payment_method' => $invoice->payment_method,
            'payment_terms' => $invoice->payment_terms,
            'due_date' => Carbon::parse($invoice->due_date)->format('d-m-y'),
            'items' => $invoice->items,
            'subtotal' => $invoice->subtotal,
            'sstPercentage' => $invoice->sstPercentage,
            'sst' => $invoice->sst,
            'final_price' => $invoice->final_price,
        ];

        $pdf = PDF::loadView('backend.invoice.invoice', $data);

        $filename = $invoice->invoice_no . '.pdf';

        return new Response(
            $pdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]
        );
    }


    public function addRemarks(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'remarks' => 'required|string|max:255',
        ]);

        $invoice = Invoice::find($request->invoice_id);
        $invoice->remarks = $request->remarks;
        $invoice->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
{
    DB::beginTransaction();

    try {
        $invoice = Invoice::with('items')->findOrFail($id);

        // Delete associated invoice items
        foreach ($invoice->items as $item) {
            $item->delete();
        }

        // Delete the PDF file if it exists
        $pdfFilePath = public_path('pdfs/' . $invoice->invoice_no . '.pdf');
        if (file_exists($pdfFilePath)) {
            unlink($pdfFilePath);
        }

        // Delete the invoice
        $invoice->delete();

        DB::commit();

        return redirect()->back()->with('success', 'Invoice deleted successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to delete invoice: ' . $e->getMessage());
    }
}
}
