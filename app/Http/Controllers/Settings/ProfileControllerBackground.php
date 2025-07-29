<?php
namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UserBackgroundCover;

class ProfileControllerBackground extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'background' => 'required|image|max:4096',
        ]);

        $user = Auth::user();
        $file = $request->file('background');
        $path = $file->store('backgrounds', 'public');

        $mime = $file->getMimeType();
        $size = $file->getSize();

        // If background exists, update it
        if ($user->background) {
            $user->background->update([
                'background_path' => $path,
                'mime_type' => $mime,
                'size' => $size,
            ]);
        } else {
            $user->background()->create([
                'background_path' => $path,
                'mime_type' => $mime,
                'size' => $size,
            ]);
        }

        // Refresh relationship to get latest
        $user->load('background');

        return response()->json([
            'message' => 'Background image updated successfully.',
            'background' => $user->background,
        ]);
    }
}