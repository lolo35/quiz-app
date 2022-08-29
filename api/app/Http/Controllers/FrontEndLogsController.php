<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class FrontEndLogsController extends Controller
{
    public function saveError(Request $request) {
        try {
            $this->validate($request, [
                'error' => "required|string"
            ]);
            $error = json_decode($request->error);
            Log::error("Front-end error", ['error' => $error]);
            return response()->json(['success' => true], 200);
        } catch (Exception $e) {
            Log::error("Error saving front-end error", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }
}
