<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuizUsers;
use Exception;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function addUser(Request $request) {
        try {
            $this->validate($request,
                [
                    'name' => "required|string",
                    'surname' => "required|string"
                ]
            );
            $insert = new QuizUsers();
            $insert->name = $request->name;
            $insert->surname = $request->surname;
            $insert->save();

            return response()->json(['success' => true, 'user' => $insert], 200);
        } catch (Exception $e) {
            Log::error("Error adding user", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }
}
