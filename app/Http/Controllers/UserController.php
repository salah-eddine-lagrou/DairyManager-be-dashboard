<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\TourneVendeur;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get all users
            $users = User::all();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully.',
                'data' => $users // Can be removed after testing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving users.',
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    // store the admin
    public function storeAdmin(Request $request)
    {
        try {
            // Validate the incoming request data, without role_id
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'plafond_vendeur' => 'nullable|numeric',
                'pda_code_access' => 'nullable|string|max:255',
                'printer_code' => 'nullable|string|max:255',
                'non_tolerated_sales_block' => 'nullable|boolean',
                'credit_limit' => 'nullable|numeric',
                'username' => 'required|string|unique:users|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'required|in:actif,inactif',
                'created_by_id' => 'nullable|exists:users,id',
                'modified_by_id' => 'nullable|exists:users,id',
                'responsable_id' => 'nullable|exists:users,id',
                'magasinier_id' => 'nullable|exists:users,id',
                'agency_id' => 'nullable|exists:agencies,id',
                'warehouse_id' => 'nullable|exists:warehouses,id',
                'zone_id' => 'nullable|exists:zones,id',
                'sector_id' => 'nullable|exists:sectors,id',
            ]);

            // Hash the password
            $validated['password'] = Hash::make($validated['password']);

            // Set the role_id to the admin role directly
            $adminRole = Role::where('role_name', 'admin')->firstOrFail();
            $validated['role_id'] = $adminRole->id;

            // Generate a unique code with the admin prefix
            $validated['code'] = $this->generateUniqueCode($adminRole->prefix);

            // Create the new admin user
            $user = User::create($validated);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Admin user created successfully.',
                'data' => $user
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
                'message' => 'An error occurred while creating the admin user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // TODO review the store function for other roles while creating the dashbord (backeoffice)
    public function storeVendeur(Request $request)
    {
        try {
            // Validate the incoming request data, without role_id
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'plafond_vendeur' => 'required|numeric',
                'printer_code' => 'nullable|string|max:255',
                'non_tolerated_sales_block' => 'required|boolean',
                'credit_limit' => 'required|numeric',
                'username' => 'required|string|unique:users|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'required|in:actif,inactif',
                'created_by_id' => 'required|exists:users,id',
                'modified_by_id' => 'nullable|exists:users,id',
                'responsable_id' => 'required|exists:users,id',
                'magasinier_id' => 'nullable|exists:users,id',
                'agency_id' => 'required|exists:agencies,id',
                'warehouse_id' => 'required|exists:warehouses,id',
                'zone_id' => 'required|exists:zones,id',
                'sector_id' => 'required|exists:sectors,id',
            ]);

            // Hash the password
            $validated['password'] = Hash::make($validated['password']);

            // Set the role_id to the vendeur role directly
            $vendeurRole = Role::where('role_name', 'vendeur')->firstOrFail();
            $validated['role_id'] = $vendeurRole->id;

            // Generate a unique code with the vendeur prefix and pda code access
            $validated['code'] = $this->generateUniqueCode($vendeurRole->prefix);
            $validated['pda_code_access'] = $this->generatePDACodeAccess();

            // Create the new vendeur user
            $user = User::create($validated);

            $tourneController = new TourneController();
            $response = $tourneController->store();
            $responseData = json_decode($response->getContent(), true);

            if ($responseData['success']) {
                $tourneId = $responseData['data']['id'];
                TourneVendeur::create([
                    'tourne_id' => $tourneId,
                    'vendeur_id' => $user->id,
                    'owner' => true,
                    'status' => 'actif'
                ]);
            }

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Vendeur user created successfully.',
                'data' => $user
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
                'message' => 'An error occurred while creating the vendeur user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // this function will assing the tourne to a vendeur user as modifier function PUT
    public function assignTourneToVendeur() {}

    public function storeResponsable(Request $request)
    {
        try {
            // Validate the incoming request data, without role_id
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'plafond_vendeur' => 'nullable|numeric',
                'printer_code' => 'nullable|string|max:255',
                'non_tolerated_sales_block' => 'nullable|boolean',
                'credit_limit' => 'nullable|numeric',
                'username' => 'required|string|unique:users|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'required|in:actif,inactif',
                'created_by_id' => 'required|exists:users,id',
                'modified_by_id' => 'nullable|exists:users,id',
                'responsable_id' => 'nullable|exists:users,id',
                'magasinier_id' => 'required|exists:users,id',
                'agency_id' => 'required|exists:agencies,id',
                'warehouse_id' => 'required|exists:warehouses,id',
                'zone_id' => 'required|exists:zones,id',
                'sector_id' => 'required|exists:sectors,id',
            ]);

            // Hash the password
            $validated['password'] = Hash::make($validated['password']);

            // Set the role_id to the responsable role directly
            $responsableRole = Role::where('role_name', 'responsable')->firstOrFail();
            $validated['role_id'] = $responsableRole->id;

            // Generate a unique code with the responsable prefix
            $validated['code'] = $this->generateUniqueCode($responsableRole->prefix);

            // Create the new responsable user
            $user = User::create($validated);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Responsable user created successfully.',
                'data' => $user
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
                'message' => 'An error occurred while creating the responsable user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeMagasinier(Request $request)
    {
        try {
            // Validate the incoming request data, without role_id
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'plafond_vendeur' => 'nullable|numeric',
                'printer_code' => 'nullable|string|max:255',
                'non_tolerated_sales_block' => 'nullable|boolean',
                'credit_limit' => 'nullable|numeric',
                'username' => 'required|string|unique:users|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'required|in:actif,inactif',
                'created_by_id' => 'required|exists:users,id',
                'modified_by_id' => 'nullable|exists:users,id',
                'responsable_id' => 'nullable|exists:users,id',
                'magasinier_id' => 'nullable|exists:users,id',
                'agency_id' => 'required|exists:agencies,id',
                'warehouse_id' => 'required|exists:warehouses,id',
                'zone_id' => 'required|exists:zones,id',
                'sector_id' => 'required|exists:sectors,id',
            ]);

            // Hash the password
            $validated['password'] = Hash::make($validated['password']);

            // Set the role_id to the magasinier role directly
            $magasinierRole = Role::where('role_name', 'magasinier')->firstOrFail();
            $validated['role_id'] = $magasinierRole->id;

            // Generate a unique code with the magasinier prefix
            $validated['code'] = $this->generateUniqueCode($magasinierRole->prefix);

            // Create the new magasinier user
            $user = User::create($validated);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Magasinier user created successfully.',
                'data' => $user
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
                'message' => 'An error occurred while creating the magasinier user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueCode($prefix)
    {
        // Get the latest code with the given prefix
        $latestCode = User::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->pluck('code')
            ->first();

        // Generate new code
        if ($latestCode) {
            $number = (int) substr($latestCode, strlen($prefix)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    private function generatePDACodeAccess()
    {
        $prefix = 'PDA';

        do {
            $randomHex = strtoupper(dechex(random_int(0x10000, 0xFFFFF)));
            $pdaCode = $prefix . $randomHex;
            $exists = User::where('pda_code_access', $pdaCode)->exists();
        } while ($exists);

        return $pdaCode;
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'User found.',
                'data' => $user // Can be removed after testing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'error' => $e->getMessage()
            ], 404);
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
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming request data
            $validated = $request->validate([
                'code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users')->ignore($id)
                ],
                'name' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|required|string|max:255',
                'plafond_vendeur' => 'sometimes|nullable|numeric',
                'pda_code_access' => 'sometimes|nullable|string|max:255',
                'printer_code' => 'sometimes|nullable|string|max:255',
                'non_tolerated_sales_block' => 'sometimes|nullable|boolean',
                'credit_limit' => 'sometimes|nullable|numeric',
                'username' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users')->ignore($id)
                ],
                'email' => [
                    'sometimes',
                    'required',
                    'string',
                    'email',
                    Rule::unique('users')->ignore($id)
                ],
                'status' => 'sometimes|required|in:actif,inactif',
                'created_by_id' => 'sometimes|nullable|exists:users,id',
                'modified_by_id' => 'sometimes|nullable|exists:users,id',
                'role_id' => 'sometimes|nullable|exists:roles,id',
                'responsable_id' => 'sometimes|nullable|exists:users,id',
                'magasinier_id' => 'sometimes|nullable|exists:users,id',
                'agency_id' => 'sometimes|nullable|exists:agencies,id',
                'warehouse_id' => 'sometimes|nullable|exists:warehouses,id',
                'zone_id' => 'sometimes|nullable|exists:zones,id',
                'sector_id' => 'sometimes|nullable|exists:sectors,id',
            ]);

            // Find the user and update it
            $user = User::findOrFail($id);

            // Update the user details
            $user->update($validated);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data' => $user // Can be removed after testing
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Set user status to inactive
    public function setInactive($id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Check if the user is already inactive
            if ($user->status === 'inactif') {
                return response()->json([
                    'success' => false,
                    'message' => 'The user is already inactive.',
                ], 400);
            }

            // Update the status to inactive
            $user->status = 'inactif';
            $user->save();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'User status updated to inactive successfully.',
                'data' => $user // Can be removed after testing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Set user status to active
    public function setActive($id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Check if the user is already active
            if ($user->status === 'actif') {
                return response()->json([
                    'success' => false,
                    'message' => 'The user is already active.',
                ], 400);
            }

            // Update the status to active
            $user->status = 'actif';
            $user->save();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'User status updated to active successfully.',
                'data' => $user // Can be removed after testing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user status.',
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
            // Find the user by ID
            $user = User::findOrFail($id);

            // Delete the user
            $user->delete();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
