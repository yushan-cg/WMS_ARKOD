<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Customer;


class ClientController extends Controller
{
    public function index()
    {
        // Retrieve all clients with their associated customers
        $clients = Client::with('customers')->get();

        return view('backend.company.client_list', compact('clients'));
    }

    // Handle the form submission
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string',
            'attention' => 'required|string',
            'tel' => 'required|string'
        ]);

        Client::create($validatedData);

        // Redirect back with a success message
       return redirect()->route('clients.index')->with('success', 'Client added successfully');
       //return response()->json($request->all());
    }

    public function edit($id)
    {
        $client = Client::findorFail($id); // Assuming you have a 'Client' model
        return view('clients.edit', compact('client'));
    }




    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        // $client = Client::where('id',$id)->first();

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            // 'email' => 'required|email|unique:clients,email,'.$id,
            'address' => 'required|string',
            'attention' => 'required|string',
            'tel' => 'required|string'
        ]);

        $client->update($validatedData);

        return redirect()->back()->with('success', 'Client updated successfully');
        // var_dump($client);
        // return $client->toJson();
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->back()->with('success', 'Client deleted successfully!');
    }

}
