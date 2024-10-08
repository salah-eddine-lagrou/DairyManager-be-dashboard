<?php

namespace App\Http\Controllers;

use App\Events\PDACodeConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'uuid' => ['required'],  // Add UUID as part of the request for device identification
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // If no user found or wrong password, return error
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'errors' => [
                    'email' => ['Identifiants invalides.'],
                    'password' => ['Identifiants invalides.'],
                ]
            ], 401);
        }

        // Case 1: First login
        if ($user->login === false && $user->status === 'actif' && is_null($user->pda_code_access) && is_null($user->device_uuid)) {
            // Generate new PDA code and store UUID
            $userController = new UserController();
            $user->pda_code_access = $userController->generatePDACodeAccess();
            $user->login = true;
            $user->device_uuid = $request['device_uuid'];
            $user->save();

            return response()->json([
                'message' => "Connexion réussie, en attente de confirmation de l'administrateur.",
                'pda_code_access' => $user->pda_code_access,
                'user' => $user,
                'case1' => true,
            ]);
        }

        // Case 2: Subsequent login on the same device, PDA code confirmed
        if ($user->login === false && $user->pda_code_access_confirmed === true && $user->device_uuid === $request['device_uuid']) {
            // Issue Sanctum token
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->login = true;
            $user->save();

            return response()->json([
                'message' => "Vous avez été connecté avec succès !",
                'token' => $token,
                'user' => $user,
                'case2' => true,
            ]);
        }

        // Case 3: Login from a different device, already logged in  todo SCENARIO sent a status with boolean yes/no to apply the process for the re-auth in the new device
        if ($user->login && $user->device_uuid !== $request['device_uuid']) {
            return response()->json([
                'message' => "Vous êtes déjà connecté sur un autre appareil. Vous souhaitez définir une nouvelle connexion sur ce nouvel appareil ?",
                'case3' => true,
            ], 403);
        }

        // Case 4: logout and login or whatever again but still not verified yet by the admin todo SCENARIO waiting your demande still not verified
        if ($user->device_uuid === $request['device_uuid'] && !$user->pda_code_access_confirmed) {
            $user->login = true;
            $user->save();

            return response()->json([
                'message' => "En attente de confirmation de l'administrateur de votre connexion",
                'pda_code_access' => $user->pda_code_access,
                'user' => $user,
                'case4' => true,
            ]);
        }

        // Case 5: If PDA code confirmed by admin, handle user access
        if ($user->pda_code_access_confirmed === true) {
            // Issue token for full access
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->login = true;
            $user->save();

            return response()->json([
                'message' => "Vous avez été connecté avec succès !",
                'token' => $token,
                'user' => $user,
                'case5' => true,
            ]);
        }

        // If no matching cases, default error message
        return response()->json([
            'message' => "La connexion a échoué. Veuillez contacter l'administration.",
        ], 403);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        // Set login to false when logging out
        $user = $request->user();
        $user->login = false;
        $user->save();

        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function scenariosCases(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validate the incoming request
        $data = $request->validate([
            'scenario' => 'required|string',
            'uuid' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé.'
            ], 404);
        }

        // Scenario 3: User is already connected on another device, and wants to log in on a new device
        if ($data['scenario'] === 'scenario3') {
            // Check if the user is logged in and the UUID is different
            if ($user->login === true && $user->device_uuid !== $data['uuid']) {
                // Log the user out of the old device
                $user->tokens()->delete(); // This will revoke all tokens (logout)

                // Reset pda_code_access_confirmed and generate new PDA code
                $userController = new UserController();
                $user->pda_code_access_confirmed = false;
                $user->pda_code_access = $userController->generatePDACodeAccess();

                // Update the device UUID
                $user->device_uuid = $data['uuid'];

                // Set login to true
                $user->login = true;

                // Save the user details
                $user->save();

                return response()->json([
                    'message' => 'Connexion sur un nouvel appareil réussie. En attente de confirmation de l\'administrateur.',
                    'pda_code_access' => $user->pda_code_access,
                ]);
            } else {
                return response()->json([
                    'message' => 'Aucune action nécessaire ou déjà sur l\'appareil actuel.'
                ], 400);
            }
        }

        // Default response if the scenario is not matched
        return response()->json([
            'message' => 'Scénario non pris en charge.'
        ], 400);
    }

    // TODO admin confirmation here
    public function confirmPdaCode($userId): \Illuminate\Http\JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404); // Return a 404 Not Found if the user does not exist
        }

        $user->pda_code_access_confirmed = true;
        $user->save();

        // Trigger the event
        event(new PDACodeConfirmed($user));

        return response()->json([
            'message' => 'PDA Code has been confirmed successfully.',
            'user' => $user, // Optionally include the user data in the response
        ], 200); // Return a 200 OK response
    }

}
