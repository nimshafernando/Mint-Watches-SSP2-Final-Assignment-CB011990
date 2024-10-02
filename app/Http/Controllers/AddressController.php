<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    // Fetch all addresses for the authenticated user
    public function index()
    {
        $addresses = Auth::user()->addresses;
        return response()->json($addresses);
    }

    // Store a new address
    public function store(Request $request)
    {
        $request->validate([
            'address_line1' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
        ]);

        $address = Auth::user()->addresses()->create($request->all());

        return response()->json(['message' => 'Address added successfully', 'address' => $address]);
    }

    // Update an existing address
    public function update(Request $request, $id)
    {
        $address = Address::findOrFail($id);

        if ($address->user_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $address->update($request->all());

        return response()->json(['message' => 'Address updated successfully', 'address' => $address]);
    }

    // Delete an address
    public function destroy($id)
    {
        $address = Address::findOrFail($id);

        if ($address->user_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $address->delete();

        return response()->json(['message' => 'Address deleted successfully']);
    }
}
