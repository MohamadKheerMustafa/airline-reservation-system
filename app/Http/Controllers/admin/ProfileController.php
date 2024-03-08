<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function updatePassword(Request $request)
    {
        $request->validate([
            "current_password" => ['required', 'string', 'min:8'],
            "new_password" => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        try {
            if (!Hash::check($request->current_password, auth()->user()->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'wrong password'
                ]);
            }
            auth()->user()->update([
                "password" => Hash::make($request->new_password)
            ]);
            auth()->user()->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => 'Updated Successfully'
            ]);
        } catch (ValidationException $validationsExc) {
            return response()->json([
                'status' => false,
                'message' => $validationsExc->getMessage(),
                'error' => $validationsExc->errors()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'something wrong',
                'error' => $e->getMessage()
            ]);
        }
    }
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            "name" => 'sometimes|required',
            "email" => 'sometimes|required|email|unique:users,email,' . auth()->id(),
            "address" => 'sometimes',
            "phone" => 'sometimes|unique:users,phone,' . auth()->id()
        ]);
        try {
            // $file = $request->file('file');
            $user = auth()->user();
            $user->update($validated);

            // if ($request->has('file')) {
            //     $mediaItems = $user->getMedia('Profiles');

            //     if (count($mediaItems) > 0) {
            //         $mediaItems->each(function ($item, $key) {
            //             $item->delete();
            //         });
            //     }
            // $user->addMedia($file)->usingName($request->name)->toMediaCollection('Profiles');
            // }
            $data  = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'phone' => $user->phone
            ];

            return response()->json([
                'status' => true,
                'message' => 'updated Successfully',
                'data' => $data
            ]);
        } catch (ValidationException $validationsExc) {
            return response()->json([
                'status' => false,
                'message' => $validationsExc->getMessage(),
                'error' => $validationsExc->errors()
            ]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'something wrong',
                'error' => $e->getMessage()
            ]);
        }
    }
}
