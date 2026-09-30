<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Lumière | Add Category
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
                        Add Category
                    </h1>

                    <p>
                        Create a new Lumière product category.
                    </p>

                </div>


                <div class="topbar-actions">

                    <a href="{{ route(
    'admin.categories.index'
) }}" class="secondary-button">
                        ← Back to Categories
                    </a>

                </div>

            </header>


            <section class="dashboard-content">


                {{-- VALIDATION SUMMARY --}}

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

                            <h2>
                                Category Information
                            </h2>

                            <p>
                                Create a category that will be
                                available to the Lumière mobile app.
                            </p>

                        </div>


                        <div class="form-header-icon">
                            ▦
                        </div>

                    </div>


                    <form method="POST" action="{{ route(
    'admin.categories.store'
) }}" enctype="multipart/form-data" class="category-create-form" data-confirm data-confirm-type="save"
                        data-confirm-title="Create Category?"
                        data-confirm-message="Are you sure you want to add this category to Lumière?"
                        data-confirm-button="Create Category">

                        @csrf


                        @include(
                            'admin.categories._form',
                            [
                                'category' => $category
                            ]
                        )


                        <div class="category-form-actions">

                            <a href="{{ route(
    'admin.categories.index'
) }}" class="cancel-button">
                                Cancel
                            </a>


                            <button type="submit" class="save-category-button">

                                <span>
                                    +
                                </span>

                                Create Category

                            </button>

                        </div>

                    </form>

                </div>


                <div class="category-safety-card">

                    <div class="safety-icon">
                        ✓
                    </div>


                    <div>

                        <h3>
                            Connected to the Mobile App
                        </h3>

                        <p>
                            New category information and images
                            are stored in Laravel and MySQL.
                            The Flutter application will retrieve
                            them through the REST API.
                        </p>

                    </div>

                </div>

            </section>

        </main>

    </div>

</body>

</html>