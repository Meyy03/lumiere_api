<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Cleanser',
                'slug' => 'cleanser',
                'icon' => 'assets/images/svg/cleanser.svg',
            ],
            [
                'id' => 2,
                'name' => 'Toner',
                'slug' => 'toner',
                'icon' => 'assets/images/svg/toner.svg',
            ],
            [
                'id' => 3,
                'name' => 'Serum',
                'slug' => 'serum',
                'icon' => 'assets/images/svg/serum.svg',
            ],
            [
                'id' => 4,
                'name' => 'Moisturizer',
                'slug' => 'moisturizer',
                'icon' => 'assets/images/svg/moisturizer.svg',
            ],
            [
                'id' => 5,
                'name' => 'Sunscreen',
                'slug' => 'sunscreen',
                'icon' => 'assets/images/svg/sunscreen.svg',
            ],
            [
                'id' => 6,
                'name' => 'Mask',
                'slug' => 'mask',
                'icon' => 'assets/images/svg/mask.svg',
            ],
            [
                'id' => 7,
                'name' => 'Eye Care',
                'slug' => 'eye-care',
                'icon' => 'assets/images/svg/eyecare.svg',
            ],
            [
                'id' => 8,
                'name' => 'Lipstick',
                'slug' => 'lipstick',
                'icon' => 'assets/images/svg/lipstick.svg',
            ],
            [
                'id' => 9,
                'name' => 'Blush',
                'slug' => 'blush',
                'icon' => 'assets/images/svg/blush.svg',
            ],
            [
                'id' => 10,
                'name' => 'Eye Shadow',
                'slug' => 'eye-shadow',
                'icon' => 'assets/images/svg/eyeshadow.svg',
            ],
            [
                'id' => 11,
                'name' => 'Cushion',
                'slug' => 'cushion',
                'icon' => 'assets/images/svg/cushion.svg',
            ],
            [
                'id' => 12,
                'name' => 'Concealer',
                'slug' => 'concealer',
                'icon' => 'assets/images/svg/concealer.svg',
            ],
            [
                'id' => 13,
                'name' => 'Eye Liner',
                'slug' => 'eye-liner',
                'icon' => 'assets/images/svg/eyeliner.svg',
            ],
            [
                'id' => 14,
                'name' => 'Perfume',
                'slug' => 'perfume',
                'icon' => 'assets/images/svg/perfume.svg',
            ],
            [
                'id' => 15,
                'name' => 'Night Cream',
                'slug' => 'night-cream',
                'icon' => 'assets/images/svg/nightcream.svg',
            ],
            [
                'id' => 16,
                'name' => 'Powder',
                'slug' => 'powder',
                'icon' => 'assets/images/svg/powder.svg',
            ],
            [
                'id' => 17,
                'name' => 'Lotion',
                'slug' => 'lotion',
                'icon' => 'assets/images/svg/lotion.svg',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                [
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'icon' => $category['icon'],

                    // Flutter SVG belongs in "icon".
                    // Laravel uploaded category photos belong in "image".
                    'image' => null,
                ]
            );
        }
    }
}