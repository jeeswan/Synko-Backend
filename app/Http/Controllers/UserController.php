<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
        ]);

        $users = User::where('email', 'like', '%' . $request->email . '%')
            ->select('id', 'first_name', 'last_name', 'email')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => trim($user->first_name . ' ' . $user->last_name),
                    'email' => $user->email,
                ];
            });

        return response()->json($users);
    }
}
