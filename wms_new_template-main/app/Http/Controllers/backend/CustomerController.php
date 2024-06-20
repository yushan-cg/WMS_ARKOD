<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Client;

class CustomerController extends Controller
{


    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    // Handle the form submission
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'address' => 'required|string',
            'attention' => 'required|string',
            'tel' => 'required|string',
            'client_id' => 'required|exists:clients,id'  // Add validation for client_id
        ]);

        Customer::create($validatedData);

        // Redirect back with a success message
        return redirect()->route('clients.index')->with('success', 'Customer added successfully');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    //update
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'address' => 'required|string',
            'attention' => 'required|string',
            'tel' => 'required|string',
            'client_id' => 'required|exists:clients,id'
        ]);

        // Update the task with the validated data
        $customer->update($validatedData);

        return redirect()->route('clients.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully!');
    }
}
