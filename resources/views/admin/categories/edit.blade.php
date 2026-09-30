<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Lumière | Edit Category
    </title>

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/categories.css') }}">
</head>


<body>

    <div class="admin-layout">

        {{-- SHARED SIDEBAR --}}

        @include('admin.partials.sidebar')


        <main class="main-content">


            {{-- TOPBAR --}}

            <header class="topbar">

                <div>

                    <h1>
                        Edit Category
                    </h1>

                    <p>
                        Update category
                        #{{ $category->id }}
                        information.
                    </p>

                </div>


                <div class="topbar-actions">

                    <a href="{{ route(
    'admin.categories.show',
    $category->id
) }}" class="secondary-button">
                        ← Back to Category
                    </a>

                </div>

            </header>


            <section class="dashboard-content">


                {{-- SUCCESS --}}

                @if(session('success'))

                    <div class="category-alert success">

                        <span>
                            ✓
                        </span>

                        <div>
                            {{ session('success') }}
                        </div>

                    </div>

                @endif


                {{-- VALIDATION --}}

                @if($errors->any())

                    <div class="category-alert error">

                        <span>
                            !
                        </span>

                        <div>

                            <strong>
                                Please check the information below.
                            </strong>

                            <p>
                                Some fields contain invalid information.
                            </p>

                        </div>

                    </div>

                @endif


                <div class="category-form-card">


                    <div class="category-form-header">

                        <div>

                            <span class="category-form-kicker">
                                CATEGORY #{{ $category->id }}
                            </span>


                            <h2>
                                Edit Category Information
                            </h2>


                            <p>
                                Update the information for
                                {{ $category->name }}.
                            </p>

                        </div>


                        <div class="form-header-icon">

                            @if(!empty($category->image))

                                                        <img src="{{ route(
                                    'category.image',
                                    [
                                        'filename' =>
                                            basename(
                                                $category->image
                                            )
                                    ]
                                ) }}" alt="{{ $category->name }}" style="
                                                                                            width: 100%;
                                                                                            height: 100%;
                                                                                            object-fit: contain;
                                                                                            border-radius: 10px;
                                                                                        ">

                            @else

                                                    {{
                                strtoupper(
                                    substr(
                                        $category->name,
                                        0,
                                        1
                                    )
                                )
                                                                            }}

                            @endif

                        </div>

                    </div>


                    {{-- SUMMARY --}}

                    <div class="category-edit-summary">

                        <div>

                            <span>
                                Current Category
                            </span>

                            <strong>
                                {{ $category->name }}
                            </strong>

                        </div>


                        <div>

                            <span>
                                Assigned Products
                            </span>

                            <strong>
                                {{ $category->products_count }}
                            </strong>

                        </div>


                        <div>

                            <span>
                                Delete Status
                            </span>


                            @if(
                                    $category->products_count > 0
                                )

                                <strong class="status-protected">
                                    Protected
                                </strong>

                            @else

                                <strong class="status-safe">
                                    Can Be Deleted
                                </strong>

                            @endif

                        </div>

                    </div>


                    <form method="POST" action="{{ route(
    'admin.categories.update',
    $category->id
) }}" enctype="multipart/form-data" data-confirm data-confirm-type="save" data-confirm-title="Save Category Changes?"
                        data-confirm-message="Are you sure you want to update this category information?"
                        data-confirm-button="Save Changes">

                        @csrf

                        @method('PUT')


                        @include(
                            'admin.categories._form',
                            [
                                'category' => $category
                            ]
                        )


                        <div class="category-form-actions">

                            <a href="{{ route(
    'admin.categories.show',
    $category->id
) }}" class="cancel-button">
                                Cancel
                            </a>


                            <button type="submit" class="save-category-button">

                                <span>
                                    ✓
                                </span>

                                Save Changes

                            </button>

                        </div>

                    </form>

                </div>

            </section>

        </main>

    </div>

</body>

</html>