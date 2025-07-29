<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAvatar;

class ProfileControllerAvatar extends Controller
{
    public function update(Request $request)
    {
        info("Updating user avatar", ['user_id' => Auth::id()]);

        try {
            $request->validate([
                'avatar' => 'required|image|max:4096',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            logger()->error('Avatar validation failed', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        }

        $user = Auth::user();
        $file = $request->file('avatar');
        $path = $file->store('avatars', 'public');

        // Delete previous avatar
        if ($user->avatar) {
            $user->avatar->delete();
        }

        $avatar = new UserAvatar([
            'avatar_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'description' => $request->input('description'),
        ]);

        $user->avatar()->save($avatar);

        return response()->json([
            'message' => 'Avatar updated successfully.',
            'avatar' => $avatar->avatar_url,
        ]);
    }

}