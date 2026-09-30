<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim(
            (string) $request->query('search', '')
        );

        $categories = Category::query()
            ->withCount('products')
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(
                        function ($subQuery) use ($search) {
                            $subQuery
                                ->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'slug',
                                    'like',
                                    '%' . $search . '%'
                                );

                            if (ctype_digit($search)) {
                                $subQuery->orWhere(
                                    'id',
                                    (int) $search
                                );
                            }
                        }
                    );
                }
            )
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $totalCategories = Category::count();
        $totalProducts = Product::count();

        return view(
            'admin.categories.index',
            compact(
                'categories',
                'search',
                'totalCategories',
                'totalProducts'
            )
        );
    }

    /**
     * Show Add Category page.
     */
    public function create(): View
    {
        $category = new Category();

        return view(
            'admin.categories.create',
            compact('category')
        );
    }

    /**
     * Create a new category.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique(
                        'categories',
                        'name'
                    ),
                ],

                'slug' => [
                    'nullable',
                    'string',
                    'max:120',
                    'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                    Rule::unique(
                        'categories',
                        'slug'
                    ),
                ],

                'image' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:4096',
                ],
            ],
            [
                'name.required' =>
                    'Please enter a category name.',

                'name.unique' =>
                    'This category name already exists.',

                'slug.regex' =>
                    'Slug may contain lowercase letters, numbers and hyphens only.',

                'slug.unique' =>
                    'This category slug already exists.',

                'image.required' =>
                    'Please choose a category image.',

                'image.image' =>
                    'The selected file must be an image.',

                'image.mimes' =>
                    'Category image must be JPG, JPEG, PNG or WEBP.',

                'image.max' =>
                    'Category image must not be larger than 4 MB.',
            ]
        );

        $name = trim(
            $validated['name']
        );

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : $this->generateUniqueSlug($name);

        $imagePath = $request
            ->file('image')
            ->store(
                'categories',
                'public'
            );

        $category = Category::create([
            'name' => $name,
            'slug' => $slug,
            'icon' => null,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route(
                'admin.categories.show',
                $category->id
            )
            ->with(
                'success',
                'Category created successfully.'
            );
    }

    /**
     * Display Category Details.
     */
    public function show(
        Category $category
    ): View {
        $category->loadCount('products');

        $category->load([
            'products' => function ($query) {
                $query->orderBy('name');
            },
        ]);

        $productCount =
            $category->products_count;

        $products =
            $category->products;

        return view(
            'admin.categories.show',
            compact(
                'category',
                'productCount',
                'products'
            )
        );
    }

    /**
     * Show Edit Category page.
     */
    public function edit(
        Category $category
    ): View {
        $category->loadCount('products');

        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    /**
     * Update a category.
     */
    public function update(
        Request $request,
        Category $category
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique(
                        'categories',
                        'name'
                    )->ignore($category->id),
                ],

                'slug' => [
                    'nullable',
                    'string',
                    'max:120',
                    'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                    Rule::unique(
                        'categories',
                        'slug'
                    )->ignore($category->id),
                ],

                'image' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:4096',
                ],
            ],
            [
                'name.required' =>
                    'Please enter a category name.',

                'name.unique' =>
                    'This category name already exists.',

                'slug.regex' =>
                    'Slug may contain lowercase letters, numbers and hyphens only.',

                'slug.unique' =>
                    'This category slug already exists.',

                'image.image' =>
                    'The selected file must be an image.',

                'image.mimes' =>
                    'Category image must be JPG, JPEG, PNG or WEBP.',

                'image.max' =>
                    'Category image must not be larger than 4 MB.',
            ]
        );

        $newName = trim(
            $validated['name']
        );

        if (!empty($validated['slug'])) {
            $slug = Str::slug(
                $validated['slug']
            );
        } elseif ($newName !== $category->name) {
            $slug = $this->generateUniqueSlug(
                $newName,
                $category->id
            );
        } else {
            $slug = $category->slug;
        }

        $category->name = $newName;
        $category->slug = $slug;

        if ($request->hasFile('image')) {
            $oldImage = $category->image;

            $newImage = $request
                ->file('image')
                ->store(
                    'categories',
                    'public'
                );

            $category->image = $newImage;

            if (
                !empty($oldImage) &&
                Str::startsWith(
                    $oldImage,
                    'categories/'
                ) &&
                Storage::disk('public')
                    ->exists($oldImage)
            ) {
                Storage::disk('public')
                    ->delete($oldImage);
            }
        }

        $category->save();

        return redirect()
            ->route(
                'admin.categories.show',
                $category->id
            )
            ->with(
                'success',
                'Category updated successfully.'
            );
    }

    /**
     * Delete a category.
     */
    public function destroy(
        Category $category
    ): RedirectResponse {
        if ($category->products()->exists()) {
            return redirect()
                ->route(
                    'admin.categories.index'
                )
                ->with(
                    'error',
                    'This category cannot be deleted because products are assigned to it.'
                );
        }

        /*
         * Delete Laravel category image.
         */
        if (
            !empty($category->image) &&
            Str::startsWith(
                $category->image,
                'categories/'
            ) &&
            Storage::disk('public')
                ->exists($category->image)
        ) {
            Storage::disk('public')
                ->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route(
                'admin.categories.index'
            )
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }

    /**
     * Generate unique category slug.
     */
    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'category';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (
            Category::query()
                ->where('slug', $slug)
                ->when(
                    $ignoreId !== null,
                    function ($query) use ($ignoreId) {
                        $query->where(
                            'id',
                            '!=',
                            $ignoreId
                        );
                    }
                )
                ->exists()
        ) {
            $slug =
                $baseSlug .
                '-' .
                $counter;

            $counter++;
        }

        return $slug;
    }
}