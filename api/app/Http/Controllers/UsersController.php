<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function addUser(Request $request) {
        try {

        } catch (Exception $e) {
            Log::error("Error adding user", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }
}
