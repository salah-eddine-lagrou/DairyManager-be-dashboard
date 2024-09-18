<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\TourneVendeur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $clients = Client::all();

            return response()->json([
                'success' => true,
                'message' => 'Clients retrieved successfully.',
                'data' => $clients
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching clients.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    // function store by vendeur les clients par vendeurs
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'qr_client' => 'nullable|string|unique:clients|max:255',
                'email' => 'required|string|email|unique:clients|max:255',
                'city' => 'required|string|max:255',
                'agency_id' => 'nullable|exists:agencies,id',
                'client_subcategory_id' => 'nullable|exists:client_subcategories,id',
                'warehouse_id' => 'nullable|exists:warehouses,id',
                'zone_id' => 'nullable|exists:zones,id',
                'sector_id' => 'nullable|exists:sectors,id',
                'contact_name' => 'nullable|string|max:255',
                'phone' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'tour_assignment_commercial' => 'nullable|boolean',
                'client_assignment_commercial' => 'nullable|boolean',
                'price_list_id' => 'nullable|exists:price_lists,id',
                'location' => 'required|string|max:255',
                'location_gps_coordinates' => 'nullable|string|max:255',
                'notification' => 'nullable|in:oui,non',
                'created_by_id' => 'required|exists:users,id',  // ! vendeur who created the client to make the owner in tourne
                'modified_by_id' => 'nullable|exists:users,id',
            ]);

            $vendeurId = $validated['created_by_id'];
            $tourneId = TourneVendeur::where('vendeur_id', $vendeurId)->first()->tourne_id;
            $validated['tourne_id'] = $tourneId;
            $validated['tour_assignment_commercial'] = true;
            $validated['client_assignment_commercial'] = true;
            $validated['status'] = 'en-attente';
            $validated['visit'] = 'oui';
            $validated['credit_limit'] = 0.00;
            $validated['credit_note_balance'] = 0.00;
            $validated['global_limit'] = 0.00;
            // TODO generation de code ICE par une fonction dynamique WE'LL SEE ABOUT THAT !
            // $validated['ice'] = '';
            // TODO generate the qr_code for client make it logical

            $validated['code'] = $this->generateUniqueCode();
            // Create a new client
            $client = Client::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully.',
                'data' => $client
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the client.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeByAdmin(Request $request)
    {
        //
    }

    private function generateUniqueCode()
    {
        // Prefix for client client code
        $prefix = "CL";

        // Get the latest code from the database
        $latestCode = Client::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->pluck('code')
            ->first();

        // Generate the next number in the sequence
        if ($latestCode) {
            $number = (int) substr($latestCode, strlen($prefix)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $client = Client::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Client retrieved successfully.',
                'data' => $client
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the client.',
                'error' => $e->getMessage()
            ], 500);
        }
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
     * TODO we'll see the update method used for completing infos of clients BY ADMIN
     */
    public function update(Request $request, $id)
    {
        try {
            $client = Client::findOrFail($id);

            // Validation
            $validated = $request->validate([
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('clients')->ignore($client->id),
                ],
                'name' => 'sometimes|required|string|max:255',
                'qr_client' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('clients')->ignore($client->id),
                ],
                'email' => [
                    'sometimes',
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('clients')->ignore($client->id),
                ],
                'ice' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('clients')->ignore($client->id),
                ],
                'city' => 'sometimes|required|string|max:255',
                'agency_id' => 'sometimes|nullable|exists:agencies,id',
                'client_subcategory_id' => 'sometimes|nullable|exists:client_subcategories,id',
                'warehouse_id' => 'sometimes|nullable|exists:warehouses,id',
                'zone_id' => 'sometimes|nullable|exists:zones,id',
                'sector_id' => 'sometimes|nullable|exists:sectors,id',
                'contact_name' => 'sometimes|nullable|string|max:255',
                'phone' => 'sometimes|required|string|max:255',
                'address' => 'sometimes|required|string|max:255',
                'tour_assignment_commercial' => 'sometimes|nullable|boolean',
                'client_assignment_commercial' => 'sometimes|nullable|boolean',
                'price_list_id' => 'sometimes|nullable|exists:price_lists,id',
                'credit_limit' => 'sometimes|nullable|numeric',
                'credit_note_balance' => 'sometimes|nullable|numeric',
                'global_limit' => 'sometimes|nullable|numeric',
                'location' => 'sometimes|required|string|max:255',
                'location_gps_coordinates' => 'sometimes|nullable|string|max:255',
                'visit' => 'sometimes|nullable|in:oui,non',
                'offert' => 'sometimes|nullable|in:oui,non',
                'notification' => 'sometimes|nullable|in:oui,non',
                'created_by_id' => 'sometimes|nullable|exists:users,id',
                'modified_by_id' => 'sometimes|nullable|exists:users,id',
                'status' => 'sometimes|required|in:en-attente,actif,inactif',
                'tourne_id' => 'sometimes|nullable|exists:tournes,id'
            ]);

            // Update client
            $client->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully.',
                'data' => $client
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the client.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function setActive($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->status = 'actif';
            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Client set to actif successfully.',
                'data' => $client
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while setting the client to actif.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function setInactive($id)
    {
        try {
             $client = Client::findOrFail($id);

             if ($client->status === 'inactif') {
                 return response()->json([
                     'success' => false,
                     'message' => 'The client is already inactive.',
                 ], 400);
             }

             $client->status = 'inactif';
             $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Client set to inactif successfully.',
                'data' => $client
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while setting the Client to inactif.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Client deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the client.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function setActif($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->status = 'actif';
            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Client status set to actif successfully.',
                'data' => $client
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating client status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function setInactif($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->status = 'inactif';
            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Client status set to inactif successfully.',
                'data' => $client
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating client status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
