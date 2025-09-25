<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\User;
use App\Models\Teacher;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    use Responses;

    /**
     * Toggle follow status (follow/unfollow)
     */
    public function toggleFollow(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'teacher_id' => 'required|exists:teachers,id'
            ]);

            if ($validator->fails()) {
                return $this->error_response($validator->errors()->first(), null);
            }

            $user = $request->user();
            if (!$user) {
                return $this->error_response('User not authenticated', null);
            }

            $teacherId = $request->teacher_id;

            // Check if already following
            $existingFollow = Follow::where('user_id', $user->id)
                                  ->where('teacher_id', $teacherId)
                                  ->first();

            if ($existingFollow) {
                // Unfollow
                $existingFollow->delete();
                return $this->success_response('Teacher unfollowed successfully', [
                    'action' => 'unfollowed',
                    'teacher_id' => $teacherId,
                    'is_following' => false
                ]);
            } else {
                // Follow
                $follow = Follow::create([
                    'user_id' => $user->id,
                    'teacher_id' => $teacherId
                ]);

                return $this->success_response('Teacher followed successfully', [
                    'action' => 'followed',
                    'teacher_id' => $teacherId,
                    'follow_id' => $follow->id,
                    'is_following' => true
                ]);
            }

        } catch (\Exception $e) {
            return $this->error_response('Failed to toggle follow status: ' . $e->getMessage(), null);
        }
    }
  
}