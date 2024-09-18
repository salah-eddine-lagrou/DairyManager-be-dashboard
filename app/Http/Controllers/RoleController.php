<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get all roles
            $roles = Role::all();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Roles retrieved successfully.',
                'data' => $roles // Can be removed after testing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving roles.',
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
        try {
            // Validate the incoming request data
            $validated = $request->validate([
                'prefix' => 'required|string|unique:roles|max:255',
                'role_name' => 'required|string|unique:roles|max:255',
                'description' => 'required|string'
            ]);

            // Create the new role
            $role = Role::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully.',
                'data' => $role
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
                'message' => 'An error occurred while creating the role.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Sync permissions with roles
    public function syncPermissions(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validate([
                'role_ids' => 'required|array',
                'role_ids.*' => 'exists:roles,id', // Ensure all role IDs exist in the 'roles' table
                'permission_ids' => 'required|array',
                'permission_ids.*' => 'exists:permissions_created,id', // Ensure all permission IDs exist in the 'permissions_created' table
            ]);

            // Sync permissions with all roles provided
            foreach ($validated['role_ids'] as $roleId) {
                $role = Role::findOrFail($roleId);
                $role->permissions()->sync($validated['permission_ids']);
            }

            return response()->json(['success' => true, 'message' => 'Permissions successfully synced with the roles.'], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'One or more roles or permissions not found.'], 404);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while syncing permissions with roles.', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
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
            // Find the role or throw a 404 exception
            $role = Role::findOrFail($id);

            // Validate the incoming request data
            $validated = $request->validate([
                'prefix' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('roles')->ignore($role->id),
                ],
                'role_name' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('roles')->ignore($role->id),
                ],
                'description' => 'sometimes|required|string'
            ]);

            // Update the role
            $role->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully.',
                'data' => $role
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the role.',
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
            $role = Role::findOrFail($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found.'
                ], 404);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the role.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
