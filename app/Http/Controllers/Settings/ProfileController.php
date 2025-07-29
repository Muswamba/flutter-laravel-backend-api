<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Update the user's profile settings.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Update the user's profile settings via API.
     */
    public function profileUpdate(ProfileUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->has('email') && $user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        $user->refresh(); // ensures updated values are returned

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user, // or new UserResource($user)
        ]);
    }

    /**
     * Get the user's profile.
     */
    public function getProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user()->load(['avatar', 'background']);

        return response()->json([
            'user' => $user,
            'notifications' => null,
            'settings' => $user->settings ?? null, // optional if user has settings relationship
        ]);
    }

}
