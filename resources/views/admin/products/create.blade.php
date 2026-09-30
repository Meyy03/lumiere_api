<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Lumière | Add Product
    </title>

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/products.css') }}">
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
                        Add Product
                    </h1>

                    <p>
                        Create a new Lumière product.
                    </p>

                </div>


                <a href="{{ route('admin.products.index') }}" class="secondary-button">
                    ← Back to Products
                </a>

            </header>


            <section class="dashboard-content">

                <div class="product-form-card">

                    <div class="form-card-header">

                        <h2>
                            Product Information
                        </h2>

                        <p>
                            Complete the information below
                            to create a product.
                        </p>

                    </div>


                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
                        data-confirm data-confirm-type="save" data-confirm-title="Create Product?"
                        data-confirm-message="Are you sure you want to add this product to the Lumière store?"
                        data-confirm-button="Create Product">

                        @csrf


                        @include(
                            'admin.products._form'
                        )


                        <div class="form-actions">

                            <a href="{{ route('admin.products.index') }}" class="cancel-button">
                                Cancel
                            </a>


                            <button type="submit" class="primary-button">
                                Create Product
                            </button>

                        </div>

                    </form>

                </div>

            </section>

        </main>

    </div>

</body>

</html>