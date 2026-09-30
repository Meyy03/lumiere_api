<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        // =====================================================
        // NORMALIZE INPUT
        // =====================================================

        $request->merge([
            'name' => trim((string) $request->input('name')),
            'email' => strtolower(
                trim((string) $request->input('email'))
            ),
        ]);

        // =====================================================
        // VALIDATION
        // =====================================================

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:254',

                    // Same stricter rule used by Flutter:
                    // example@gmail.c   -> invalid
                    // example@gmail.co  -> invalid
                    // example@gmail.com -> valid
                    'regex:/^[A-Za-z0-9._%+\-]+@[A-Za-z0-9\-]+(?:\.[A-Za-z0-9\-]+)*\.[A-Za-z]{3,}$/',

                    'unique:users,email',
                ],

                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    'max:64',

                    // No spaces
                    'not_regex:/\s/',

                    // Strong password:
                    // uppercase + lowercase + number + symbol
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                ],

                'phone' => [
                    'nullable',
                    'string',
                    'max:30',
                ],

                'shipping_address' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],

                'province' => [
                    'nullable',
                    'string',
                    'max:100',
                ],
            ],
            [
                // =================================================
                // CUSTOM VALIDATION MESSAGES
                // =================================================

                'name.required' =>
                    'Full name is required.',

                'name.min' =>
                    'Full name must contain at least 2 characters.',

                'email.required' =>
                    'Email is required.',

                'email.email' =>
                    'Enter a valid email address.',

                'email.regex' =>
                    'Enter a complete email, e.g. name@example.com.',

                'email.unique' =>
                    'This email is already registered.',

                'password.required' =>
                    'Password is required.',

                'password.confirmed' =>
                    'Passwords do not match.',

                'password.max' =>
                    'Password must not exceed 64 characters.',

                'password.not_regex' =>
                    'Password cannot contain spaces.',
            ]
        );

        // =====================================================
        // CREATE USER
        // =====================================================

        $user = User::create([
            'name' => $validated['name'],

            'email' => $validated['email'],

            // Never save plain-text passwords.
            'password' => Hash::make(
                $validated['password']
            ),

            'phone' =>
                $validated['phone'] ?? null,

            'shipping_address' =>
                $validated['shipping_address'] ?? null,

            'province' =>
                $validated['province'] ?? null,
        ]);

        // =====================================================
        // CREATE SANCTUM TOKEN
        // =====================================================

        $token = $user
            ->createToken('lumiere-mobile')
            ->plainTextToken;

        // =====================================================
        // RESPONSE
        // =====================================================

        return response()->json([
            'success' => true,

            'message' =>
                'Registration successful.',

            'data' => [
                'token' => $token,

                'token_type' => 'Bearer',

                'user' => $user,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        // =====================================================
        // NORMALIZE EMAIL
        // =====================================================

        $request->merge([
            'email' => strtolower(
                trim((string) $request->input('email'))
            ),
        ]);

        // =====================================================
        // LOGIN VALIDATION
        // =====================================================

        $validated = $request->validate(
            [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:254',
                ],

                'password' => [
                    'required',
                    'string',
                ],
            ],
            [
                'email.required' =>
                    'Email is required.',

                'email.email' =>
                    'Enter a valid email address.',

                'password.required' =>
                    'Password is required.',
            ]
        );

        // =====================================================
        // FIND USER
        // =====================================================

        $user = User::where(
            'email',
            $validated['email']
        )->first();

        // =====================================================
        // VERIFY PASSWORD
        // =====================================================

        if (
            !$user ||
            $user->role !== 'customer' ||
            !Hash::check(
                $validated['password'],
                $user->password
            )
        ) {
            throw ValidationException::withMessages([
                'email' => [
                    'The email or password is incorrect.',
                ],
            ]);
        }

        // =====================================================
        // REMOVE OLD MOBILE TOKENS
        // =====================================================

        $user->tokens()
            ->where(
                'name',
                'lumiere-mobile'
            )
            ->delete();

        // =====================================================
        // CREATE NEW TOKEN
        // =====================================================

        $token = $user
            ->createToken('lumiere-mobile')
            ->plainTextToken;

        // =====================================================
        // RESPONSE
        // =====================================================

        return response()->json([
            'success' => true,

            'message' =>
                'Login successful.',

            'data' => [
                'token' => $token,

                'token_type' => 'Bearer',

                'user' => $user,
            ],
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,

            'message' =>
                'User retrieved successfully.',

            'data' => $request->user(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        // Delete only the token used by this request.
        $request->user()
            ->currentAccessToken()
                ?->delete();

        return response()->json([
            'success' => true,

            'message' =>
                'Logout successful.',
        ]);
    }
}