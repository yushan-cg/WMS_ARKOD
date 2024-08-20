<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


use App\Models\Client;
use App\Models\Customer;
use App\Models\Waybill;

class WaybillController extends Controller
{
    public function index()
    {
        $waybills = Waybill::all();
        return view('backend.Invoice.waybill_list', compact('waybills'));
    }

    public function create()
    {
        return view('backend.invoice.waybill_form');
    }

    public function searchShipper(Request $request)
    {
        $query = $request->get('query');

        // Search in both Clients and Customers tables
        $shippers = DB::table('clients')
                    ->select('name', 'address', 'postcode', 'attention', 'tel')
                    ->where('name', 'like', "$query%")
                    ->union(DB::table('customers')
                            ->select('name', 'address', 'postcode', 'attention', 'tel')
                            ->where('name', 'like', "$query%"))
                    ->get();

        $html = '';
        foreach ($shippers as $shipper) {
            $html .= '<div class="dropdown-item" data-details=\'' . json_encode($shipper) . '\'>' . $shipper->name . '</div>';
        }

        return response()->json($html);
    }

    public function searchReceiver(Request $request)
    {
        $query = $request->get('query');

        // Search in both Clients and Customers tables
        $receivers = DB::table('clients')
                    ->select('name', 'address', 'postcode', 'attention', 'tel')
                    ->where('name', 'like', "$query%")
                    ->union(DB::table('customers')
                            ->select('name', 'address', 'postcode', 'attention', 'tel')
                            ->where('name', 'like', "$query%"))
                    ->get();

        $html = '';
        foreach ($receivers as $receiver) {
            $html .= '<div class="dropdown-item" data-details=\'' . json_encode($receiver) . '\'>' . $receiver->name . '</div>';
        }

        return response()->json($html);
    }

    public function store(Request $request)
    {
        // Validation rules
        $request->validate([
            'no' => 'required|string',
            'customer_id' => 'required|string',
            'service_type' => 'required|string',
            'shipper_details.name' => 'required|string',
            'shipper_details.address' => 'required|string',
            'shipper_details.postcode' => 'required|string',
            'shipper_details.attention' => 'nullable|string',
            'shipper_details.tel' => 'required|string',
            'receiver_details.name' => 'required|string',
            'receiver_details.address' => 'required|string',
            'receiver_details.postcode' => 'required|string',
            'receiver_details.attention' => 'nullable|string',
            'receiver_details.tel' => 'required|string',
        ]);

        // Handle order products data (optional fields)
        $orderProducts = $request->input('order_products', []);

        // Ensure nullable fields are set to null if not provided
        $nullableFields = ['content', 'category', 'size', 'total_weight'];
        foreach ($nullableFields as $field) {
            if (!isset($orderProducts[$field])) {
                $orderProducts[$field] = null;
            }
        }

        $dateNow = Carbon::now();  // Current date and time
        $dateWb = $dateNow->format('dmy');
        // Format current date for database storage
        $formattedDate = $dateNow->format('Y-m-d');

        // Create a new Waybill instance
        $waybill = new Waybill();
        $waybill->date = $formattedDate;
        $waybill->no = $request->input('no');
        $waybill->waybill_no = 'ARKDWB-' . $dateWb . '-' . $request->input('no') . $request->input('customer_id');
        $waybill->customer_id = $request->input('customer_id');
        $waybill->service_type = $request->input('service_type');
        $waybill->shipper_name = $request->input('shipper_details.name');
        $waybill->shipper_address = $request->input('shipper_details.address');
        $waybill->shipper_postcode = $request->input('shipper_details.postcode');
        $waybill->shipper_attention = $request->input('shipper_details.attention');
        $waybill->shipper_tel = $request->input('shipper_details.tel');
        $waybill->receiver_name = $request->input('receiver_details.name');
        $waybill->receiver_address = $request->input('receiver_details.address');
        $waybill->receiver_postcode = $request->input('receiver_details.postcode');
        $waybill->receiver_attention = $request->input('receiver_details.attention');
        $waybill->receiver_tel = $request->input('receiver_details.tel');
        $waybill->order_content = $request->input('order_products.content');
        $waybill->order_category = $request->input('order_products.category');
        $waybill->order_size = $request->input('order_products.size');
        $waybill->order_total_weight = $request->input('order_products.total_weight');
        $waybill->save();

        // Data for PDF
        $data = [
            'customer_id' => $waybill->customer_id,
            'waybill_no' => $waybill->waybill_no,
            'date' => Carbon::parse($waybill->date)->format('d-m-y'),
            'service_type' => $waybill->service_type,
            'shipper' => [
                'name' => $waybill->shipper_name,
                'address' => $waybill->shipper_address,
                'postcode' => $waybill->shipper_postcode,
                'attention' => $waybill->shipper_attention,
                'tel' => $waybill->shipper_tel,
            ],
            'receiver' => [
                'name' => $waybill->receiver_name,
                'address' => $waybill->receiver_address,
                'postcode' => $waybill->receiver_postcode,
                'attention' => $waybill->receiver_attention,
                'tel' => $waybill->receiver_tel,
            ],
            'order' => [
                'content' => $waybill->order_content,
                'category' => $waybill->order_category,
                'size' => $waybill->order_size,
                'total_weight' => $waybill->order_total_weight,
            ],
        ];

        // Generate PDF
        $pdf = PDF::loadView('backend.invoice.waybill', $data);

        // Set filename dynamically based on waybill_no
        $filename = $waybill->waybill_no . '.pdf';
        $filepath = public_path('pdfs/' . $filename);
        $pdf->save($filepath);

        return response()->json([
            'pdf_url' => url('pdfs/' . $filename),
            'redirect_url' => route('waybills.index')
        ]);
    }



    public function generatePdf($id)
    {
        $waybill = Waybill::findOrFail($id);

        // Data for PDF
        $data = [
            'customer_id' => $waybill->customer_id,
            'waybill_no' => $waybill->waybill_no,
            'date' => Carbon::parse($waybill->date)->format('d-m-y'),
            'service_type' => $waybill->service_type,
            'shipper' => [
                'name' => $waybill->shipper_name,
                'address' => $waybill->shipper_address,
                'postcode' => $waybill->shipper_postcode,
                'attention' => $waybill->shipper_attention,
                'tel' => $waybill->shipper_tel,
            ],
            'receiver' => [
                'name' => $waybill->receiver_name,
                'address' => $waybill->receiver_address,
                'postcode' => $waybill->receiver_postcode,
                'attention' => $waybill->receiver_attention,
                'tel' => $waybill->receiver_tel,
            ],
            'order' => [
                'content' => $waybill->order_content,
                'category' => $waybill->order_category,
                'size' => $waybill->order_size,
                'total_weight' => $waybill->order_total_weight,
            ],
        ];

        // Generate PDF
        $pdf = PDF::loadView('backend.invoice.waybill', $data);

        $filename = $waybill->waybill_no . '.pdf';

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
        'waybill_id' => 'required|exists:waybills,id',
        'remarks' => 'required|string|max:255',
    ]);

    $waybill = Waybill::find($request->waybill_id);
    $waybill->remarks = $request->remarks;
    $waybill->save();

    return response()->json(['success' => true]);
}


    public function destroy($id)
    {
        $waybill = Waybill::findOrFail($id);

    // to delete pdf from public/pdfs directory
    // Construct the PDF file path using the waybill number
    $pdfFilePath = public_path('pdfs/' . $waybill->waybill_no . '.pdf');

    // Delete the PDF file if it exists
    if (file_exists($pdfFilePath)) {
        unlink($pdfFilePath);
    }

        $waybill->delete();

        return redirect()->back()->with('success', 'Waybill deleted successfully!');
    }

}

