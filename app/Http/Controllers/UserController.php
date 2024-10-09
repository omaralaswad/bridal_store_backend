<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Beneficiaries;
use App\Models\Archives;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function delete_user($id)
    {

        $user = User::find($id);
        $result = $user->delete();
        if ($result) {
            return ["result" => "record has been deleted" . $id];
        } else {
            return ["result" => "delete has failed"];
        }

    }

    public function update_user(Request $request, $id)
    {
        // Find the user by ID
        $record = User::find($id);

        // Check if the user exists
        if (!$record) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Validate the incoming request (you can customize this as needed)
        $request->validate([
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'age' => 'sometimes|integer',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6|confirmed',
            'role' => 'sometimes|in:admin,user', // Only allow specific roles
        ]);

        // Update the fields
        $data = $request->all();
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                // If password is provided, hash it before saving
                $record->$key = Hash::make($value);
            } else {
                $record->$key = $value;
            }
        }

        // Save the updated user record
        $record->save();

        return response()->json(['message' => 'Record updated successfully', 'data' => $record], 200);
    }

    public function changePassword(Request $request)
{
    // Validate incoming request
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:6|different:current_password',
    ]);

    // Retrieve user or fail
    $user = User::findOrFail($request->input('user_id'));

    // Verify the current password
    if (!Hash::check($request->input('current_password'), $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Incorrect current password',
        ], 400);
    }

    // Change the password
    $user->password = Hash::make($request->input('new_password'));
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Password changed successfully',
    ]);
}






}
