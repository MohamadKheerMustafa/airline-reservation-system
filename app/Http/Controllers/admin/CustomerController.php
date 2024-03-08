<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Http\Resources\UserInfoResource;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerController extends Controller
{
    public function admins()
    {
        $Users = User::where('is_admin', 1)->orWhere('is_Employee', 1)->get();
        return UserInfoResource::collection($Users);
    }
    public function index()
    {
        $Users = User::customer()->withCount('tickets')->with('media')->get();
        return UserInfoResource::collection($Users);
    }

    public function show($id)
    {
        $Users = User::findOrFail($id)->tickets;
        return new TicketResource($Users[0]);
    }

    public function destroy($id)
    {
        try {
            $Users = User::findOrFail($id);
            $Users->delete();
            return response()->json([
                'status' => true,
                'message' => 'Deleted Successfully'
            ]);
        } catch (ModelNotFoundException $ModelNotFoundEx) {
            return response()->json([
                'status' => false,
                'message' => 'Users Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchUser($query)
    {
        $Users = User::where('name', 'NOT LIKE', 'admin')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'LIKE', '%' . $query . '%')
                    ->orWhere('email', 'LIKE', '%' . $query . '%')
                    ->orWhere('phone', 'LIKE', '%' . $query . '%')
                    ->orWhere('address', 'LIKE', '%' . $query . '%');
            })
            ->get();
        if (count($Users)) {
            return Response()->json($Users);
        } else {
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }
}
