<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use App\Models\Product;
use App\Models\User;
use App\Models\Partner;
use App\Models\Client;

use App\Models\Notification;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function listProduct(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();
        $user_id = $user->id;


        // Initialize the base query
        $query = DB::table('products')
            ->leftJoin('users', 'products.uid', '=', 'users.id')
            ->leftJoin('clients', 'products.client_id', '=', 'clients.id')
            ->select(
                'products.id',
                'products.name as product_name',
                'products.SKU',
                'products.product_desc',
                'products.expired_date',
                DB::raw("CONCAT(products.Img) as Img"),
                'products.UOM',
                'products.weight_per_unit',
                'products.status',
                'products.updated_at',
                'clients.id as client_id',
                'clients.name as client_name',
                'users.name as user_name'
            );

        // Check user role and modify the query accordingly
        if ($user->role == 1) {
            // Admin: get all products
            $list = $query->get();
            $clients = Client::all();
        } elseif ($user->role == 2) {
            // Picker: get products owned by the user
            //$list = $query->where('products.uid', $user_id)->get();  WRONG!! picker not associated with any product
        } elseif ($user->role == 3) {
            // Client: get products owned by the user based on client_id
            //not based on uid as role 1 can also add product

            $client = Client::where('uid', $user_id)->first();

            if ($client) {
                $client_id = $client->id;
            } else {
                // Handle the case where no client is found for the user
                abort(403, 'Client not found for the authenticated user.');
            }

            $list = $query->where('products.client_id', $client_id)->get();
            $clients = Client::where('id', $client_id)->get();

        } else {
            // Handle case where user has an unknown role
            abort(403, 'Unauthorized action.');
        }

        // Return the view with the list of products
        return view('backend.product.list_product', compact('list', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function insertProduct(Request $request)
    {
        $user = auth()->user();

        // Define validation rules
        $validationRules = [
            'product_name' => 'required|string|max:255',
            'SKU' => 'required|string|max:255',
            'product_desc' => 'nullable|string',
            'expired_date' => 'nullable|date',
            'UOM' => 'required|string|max:50',
            'weight_per_unit' => 'numeric|nullable',
            'client_id' => 'required|integer|exists:clients,id',
            'Img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
        ];

        // Validate the incoming request data
        $validated = $request->validate($validationRules);

        // Determine status based on user role
        $status = $user->role == 1 ? 'Approved' : ($user->role == 3 ? 'Pending' : null);
        if (is_null($status)) {
            abort(403, 'Unauthorized action.');
        }

        // Prepare the data for insertion
        $data = [
            'name' => $validated['product_name'],
            'SKU' => $validated['SKU'],
            'product_desc' => $validated['product_desc'] ?? null,
            'expired_date' => $validated['expired_date'] ?? null,
            'UOM' => $validated['UOM'],
            'weight_per_unit' => $validated['weight_per_unit'] ?? null,
            'status' => $status,
            'updated_at' => now(),
            'uid' => $user->id,
            'client_id' => $validated['client_id'],
        ];

        // Handle file upload
        if ($request->hasFile('Img')) {
            $file = $request->file('Img');
            $filename = date('YmdHi') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('product_img'), $filename);
            $data['Img'] = $filename;
        }

        DB::beginTransaction();
        try {
            // Insert the product and get the inserted ID
            $insertedId = DB::table('products')->insertGetId($data);
            DB::commit();

            if ($insertedId) {
                if ($status == 'Pending') {
                    // Fetch the newly created product
                    $product = Product::findOrFail($insertedId);

                    // Fetch all users with role 1 (e.g., admins)
                    $admins = User::where('role', 1)->get();

                    // Create notifications for all users with role 1
                    foreach ($admins as $admin) {
                        Notification::create([
                            'uid' => $admin->id,
                            'message' => 'Product name ' . $product->name . ' is waiting to be approved.',
                            'created_at' => now(),
                        ]);
                    }
                }

                return redirect()->route('product.index')->with('success', 'Product Created Successfully!');
            } else {
                return redirect()->route('product.index')->with('error', 'Failed to create product.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('product.index')->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }


    public function editProduct($id)
    {
        // Initialize the base query
        $query = DB::table('products')
            ->leftJoin('users', 'products.uid', '=', 'users.id')
            ->leftJoin('clients', 'products.client_id', '=', 'clients.id')
            ->select(
                'products.id',
                'products.name as product_name',
                'products.SKU',
                'products.product_desc',
                'products.expired_date',
                'products.Img',
                'products.UOM',
                'products.weight_per_unit',
                'products.updated_at',
                'clients.id as client_id',
                'clients.name as client_name',
                'users.name as user_name',
                'users.id as uid'
            );

        $edit = $query->where('products.id', $id)->first();
        $clients = DB::table('clients')->where('clients.uid', 'uid');

        return view('backend.product.edit_product', compact('edit', 'clients'));
    }

    public function updateProduct(Request $request, $id)
    {
        // return $request;  {DEBUGGING}

        $user = auth()->user();
        $user_id = $user->id;



        // Define validation rules
        $validationRules = [
            'product_name' => 'required|string|max:255',
            'SKU' => 'required|string|max:255',
            'product_desc' => 'nullable|string',
            'expired_date' => 'nullable|date',
            'UOM' => 'required|string|max:50',
            'weight_per_unit' => 'numeric',
            // 'client_id' => 'required|integer|exists:clients,id',
            'Img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
        ];

        // Validate the incoming request data
        $validated = $request->validate($validationRules);

        // Determine status based on user role
        $status = $user->role == 1 ? 'Approved' : ($user->role == 3 ? 'Pending' : null);
        if (is_null($status)) {
            abort(403, 'Unauthorized action.');
        }

        // Prepare the data for updating
        $data = [
            'name' => $validated['product_name'],
            'SKU' => $validated['SKU'],
            'product_desc' => $validated['product_desc'] ?? null,
            'expired_date' => $validated['expired_date'] ?? null,
            'UOM' => $validated['UOM'],
            'weight_per_unit' => $validated['weight_per_unit'] ?? null,
            'status' => $status,
            'updated_at' => now(),
            // 'client_id' => $validated['client_id'],
        ];

        // Retrieve the existing product by ID
        $product = Product::findOrFail($id);

        // Handle file upload
        if ($request->hasFile('Img')) {
            // Construct the full path to the existing image
            $existingImagePath = public_path('product_img/' . $product->Img);

            // Check if the existing image file exists and delete it
            if (file_exists($existingImagePath)) {
                unlink($existingImagePath);
            }

            // Upload the new image
            $file = $request->file('Img');
            $filename = date('YmdHi') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('product_img'), $filename);
            $data['Img'] = $filename;
        }

        DB::beginTransaction();
        try {
            $update = DB::table('products')->where('id', $id)->update($data);
            DB::commit();

            if ($update) {
                //return "a";
                    // Fetch the newly created product
                    //$product = Product::findOrFail($update);

                    // Create notifications for all users with role 1

                    $product = Product::findOrFail($id);

                    $client = Client::where('id', $product->client_id)->first();

                    if ($client) {

                        //this notification for uid based on client table
                        //because uid in product for who add/update the table
                        $usersToNotify = User::join('clients', 'clients.uid', '=', 'users.id')
                        ->where('clients.id', $product->client_id)
                        ->where('users.role', 3)
                        ->select('users.id')
                        ->get();

                        foreach ($usersToNotify as $user) {
                            Notification::create([
                                'uid' => $user->id,
                                'message' => 'Product name ' . $product->name . ' has been updated.',
                                'created_at' => now(),
                            ]);
                        }

                    } else {
                        // Handle the case where no client is found for the user
                        abort(403, 'Client not found for the authenticated user.');
                    }

                return redirect()->route('product.index')->with('success', 'Product Updated Successfully!');
            } else {
                //return "b";
                return redirect()->route('product.index')->with('error', 'Failed to update product.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
                //return $e;
            return redirect()->route('product.index')->with('error', 'Failed to update product.');
        }
    }

    public function deleteProduct($id)
    {
        // Retrieve the product by ID, if not found throw a 404 error
        $product = Product::findOrFail($id);

        // Begin a transaction to ensure both product and image are deleted together
        DB::beginTransaction();
        try {
            // If the product has an image, delete it from storage
            if ($product->Img) {
                // Construct the full path to the image
                $imagePath = public_path('product_img/' . $product->Img);

                // Check if the image file exists and delete it
                if (file_exists($imagePath)) {
                    if (!unlink($imagePath)) {
                        throw new \Exception('Failed to delete image: ' . $imagePath);
                    }
                }
            }

            // Delete the product from the database
            $product->delete();

            // Commit the transaction
            DB::commit();

            // Prepare the success notification
            $notification = array(
                'message' => 'Product Deleted Successfully',
                'alert-type' => 'success'
            );

        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Prepare the error notification
            $notification = array(
                'message' => 'Error: ' . $e->getMessage(),
                'alert-type' => 'error'
            );
        }

        // Redirect back with the notification
        return redirect()->back()->with($notification);
    }

    public function approveProduct($id)
    {
        $user = auth()->user();
        if ($user->role != 1) {
            abort(403, 'Unauthorized action.');
        }

        $product = Product::findOrFail($id);
        $product->status = 'Approved';
        $product->save();

        $client = Client::where('id', $product->client_id)->first();

        Notification::create([
            'uid' => $product->uid,
            'message' => '  Your product name ' . $product->name . ' has been approved.',
            'created_at' => now(),
        ]);

        return redirect()->route('product.index')->with('success', 'Product Approved Successfully!');
    }

    public function rejectProduct($id)
    {
        $user = auth()->user();
        if ($user->role != 1) {
            abort(403, 'Unauthorized action.');
        }

        $product = Product::findOrFail($id);
        $product->status = 'Rejected';
        $product->save();

        Notification::create([
            'uid' => $product->uid,
            'message' => '  Your product name ' . $product->name . ' has been rejected.',
            'created_at' => now(),
        ]);

        return redirect()->route('product.index')->with('success', 'Product Rejected Successfully!');
    }
}
