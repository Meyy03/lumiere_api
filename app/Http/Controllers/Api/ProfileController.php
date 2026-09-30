<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{

    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully.',
            'data' => $this->profileData(
                $request->user()
            ),
        ]);
    }


    public function update(Request $request): JsonResponse
    {
        $user = $request->user();


        $validated = $request->validate([

            /**
             * NAME
             */
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],


            /**
             * EMAIL
             */
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                        $user->id
                    ),
            ],


            /**
             * PHONE
             */
            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:30',
            ],


            /**
             * SHIPPING ADDRESS
             */
            'shipping_address' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000',
            ],


            /**
             * PROVINCE
             */
            'province' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],


            'shopping_preference' => [
                'sometimes',
                'required',
                'string',

                Rule::in([
                    'female',
                    'male',
                    'all',
                ]),
            ],
        ], [

            /**
             * Shopping Preference Messages
             */
            'shopping_preference.required' =>
                'Please select a shopping preference.',

            'shopping_preference.in' =>
                'Shopping preference must be Women, Men, or All.',
        ]);


        /**
         * Clean Name
         */
        if (
            isset(
            $validated['name']
        )
        ) {
            $validated['name'] =
                trim(
                    $validated['name']
                );
        }


        /**
         * Clean Email
         */
        if (
            isset(
            $validated['email']
        )
        ) {
            $validated['email'] =
                strtolower(
                    trim(
                        $validated['email']
                    )
                );
        }


        /**
         * Clean Phone
         */
        if (
            isset(
            $validated['phone']
        )
        ) {
            $validated['phone'] =
                trim(
                    $validated['phone']
                );
        }


        /**
         * Clean Shipping Address
         */
        if (
            isset(
            $validated['shipping_address']
        )
        ) {
            $validated['shipping_address'] =
                trim(
                    $validated['shipping_address']
                );
        }


        /**
         * Clean Province
         */
        if (
            isset(
            $validated['province']
        )
        ) {
            $validated['province'] =
                trim(
                    $validated['province']
                );
        }


        /**
         * Save Changes
         */
        $user->update(
            $validated
        );


        return response()->json([
            'success' => true,
            'message' =>
                'Profile updated successfully.',

            'data' =>
                $this->profileData(
                    $user->fresh()
                ),
        ]);
    }


    public function updateImage(
        Request $request
    ): JsonResponse {

        $user =
            $request->user();


        $request->validate([

            'profile_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

        ]);


        $image =
            $request->file(
                'profile_image'
            );


        if (!$image) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Profile image was not received.',
            ], 422);
        }


        /**
         * Delete previous profile image.
         */
        if (
            $user->profile_image &&
            Storage::disk('public')
                ->exists(
                    $user->profile_image
                )
        ) {

            Storage::disk('public')
                ->delete(
                    $user->profile_image
                );
        }


        /**
         * Store New Profile Image
         */
        $path =
            $image->store(
                'profiles',
                'public'
            );


        $user->profile_image =
            $path;


        $user->save();


        return response()->json([
            'success' => true,
            'message' =>
                'Profile image updated successfully.',

            'data' =>
                $this->profileData(
                    $user->fresh()
                ),
        ]);
    }


    private function profileData(
        User $user
    ): array {

        return [

            'id' =>
                $user->id,


            'name' =>
                $user->name,


            'email' =>
                $user->email,


            'phone' =>
                $user->phone,


            'profile_image' =>
                $user->profile_image,


            /**
             * Full profile image URL
             */
            'profile_image_url' =>
                $user->profile_image
                ? url(
                    '/profile-images/' .
                    rawurlencode(
                        basename(
                            $user->profile_image
                        )
                    )
                )
                : null,


            'shipping_address' =>
                $user->shipping_address,


            'province' =>
                $user->province,

            'shopping_preference' =>
                $user->shopping_preference,


            'role' =>
                $user->role,


            'created_at' =>
                $user->created_at,


            'updated_at' =>
                $user->updated_at,
        ];
    }
}