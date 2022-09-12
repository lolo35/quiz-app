<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Rewarded extends Model
{
    use HasFactory;
    protected $table = "rewardeds";
    protected $fillable = ['user_id', 'quiz_id', 'rewarded'];
    protected $hidden = [];

    public function checkRewardState(int $user_id):array {
        try {
            $data = $this::where('user_id', '=', $user_id)->first();
            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            Log::error("Error fetching reward state from DB", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function insert(int $user_id, int $quiz_id):array {
        try {
            $insert = new $this();
            $insert->user_id = $user_id;
            $insert->quiz_id = $quiz_id;
            $insert->rewarded = true;
            $insert->save();

            return ['success' => true];
        } catch (Exception $e) {
            Log::error("Error inserting new values", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
