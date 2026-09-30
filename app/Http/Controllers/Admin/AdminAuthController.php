<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {

        if (Auth::check()) {

            $feedback = session('admin_feedback');

            $showLoginSuccess =
                is_array($feedback) &&
                ($feedback['type'] ?? null) === 'success' &&
                !empty($feedback['redirect_url']);

            if (!$showLoginSuccess) {
                return redirect()
                    ->route('admin.dashboard');
            }
        }

        return view('admin.auth.login');
    }

    public function login(
        Request $request
    ): RedirectResponse {

        $validator = Validator::make(
            $request->all(),
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
                    'min:8',
                    'max:64',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9])\S{8,64}$/',
                ],
            ],
            [
                'email.required' =>
                    'Please enter your email address.',

                'email.email' =>
                    'Enter a complete email address, e.g. admin@example.com.',

                'email.max' =>
                    'The email address is too long.',

                'password.required' =>
                    'Please enter your password.',

                'password.min' =>
                    'Use 8+ characters with uppercase, lowercase, number and symbol, with no spaces.',

                'password.max' =>
                    'Password must not exceed 64 characters.',

                'password.regex' =>
                    'Use 8+ characters with uppercase, lowercase, number and symbol, with no spaces.',
            ]
        );

        if ($validator->fails()) {

            return redirect()
                ->route('admin.login')
                ->with(
                    'admin_feedback',
                    [
                        'type' => 'error',

                        'title' => 'Login Failed',

                        'message' =>
                            $validator
                                ->errors()
                                ->first(),

                        'button' => 'Try Again',

                        'clear_login' => true,
                    ]
                );
        }


        /*
         * =====================================================
         * LOGIN CREDENTIALS
         * =====================================================
         */

        $credentials = [
            'email' => strtolower(
                trim(
                    $request->input('email')
                )
            ),

            'password' =>
                $request->input('password'),

            'role' => 'admin',
        ];


        $remember =
            $request->boolean('remember');


        /*
         * =====================================================
         * LOGIN SUCCESS
         * =====================================================
         */

        if (
            Auth::attempt(
                $credentials,
                $remember
            )
        ) {

            $request
                ->session()
                ->regenerate();


            $adminName =
                Auth::user()->name;

            return redirect()
                ->route('admin.login')
                ->with(
                    'admin_feedback',
                    [
                        'type' =>
                            'success',

                        'title' =>
                            'Login Successful',

                        'message' =>
                            'Welcome back, ' .
                            $adminName .
                            '. You are now signed in to the Lumière Admin System.',

                        'button' =>
                            'Continue',

                        'redirect_url' =>
                            route(
                                'admin.dashboard'
                            ),
                    ]
                );
        }


        /*
         * =====================================================
         * WRONG EMAIL / PASSWORD
         * =====================================================
         */

        return redirect()
            ->route('admin.login')
            ->with(
                'admin_feedback',
                [
                    'type' =>
                        'error',

                    'title' =>
                        'Login Failed',

                    'message' =>
                        'The email address or password is incorrect. Please check your credentials and try again.',

                    'button' =>
                        'Try Again',

                    'clear_login' =>
                        true,
                ]
            );
    }

    public function logout(
        Request $request
    ): RedirectResponse {

        Auth::logout();


        $request
            ->session()
            ->invalidate();


        $request
            ->session()
            ->regenerateToken();


        return redirect()
            ->route('admin.login')
            ->with(
                'admin_feedback',
                [
                    'type' =>
                        'success',

                    'title' =>
                        'Logged Out Successfully',

                    'message' =>
                        'You have been safely signed out of the Lumière Admin System.',

                    'button' =>
                        'OK',
                ]
            );
    }
}