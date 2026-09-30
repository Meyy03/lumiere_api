<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Lumière | Edit Product
    </title>


    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/products.css') }}">

</head>


<body>

    <div class="admin-layout">


        {{-- =====================================================
        SHARED SIDEBAR
        The sidebar already includes confirm-modal.blade.php
        ===================================================== --}}

        @include('admin.partials.sidebar')



        {{-- =====================================================
        MAIN CONTENT
        ===================================================== --}}

        <main class="main-content">


            {{-- =================================================
            TOPBAR
            ================================================= --}}

            <header class="topbar">

                <div>

                    <h1>
                        Edit Product
                    </h1>

                    <p>
                        Update #{{ $product->id }}
                        {{ $product->name }}.
                    </p>

                </div>


                <a href="{{ route(
    'admin.products.show',
    $product->id
) }}" class="secondary-button">
                    ← Back to Details
                </a>

            </header>



            {{-- =================================================
            PAGE CONTENT
            ================================================= --}}

            <section class="dashboard-content">


                <div class="product-form-card">


                    {{-- =========================================
                    FORM HEADER
                    ========================================= --}}

                    <div class="form-card-header">

                        <h2>
                            Product Information
                        </h2>

                        <p>
                            Update the product information below.
                        </p>

                    </div>



                    {{-- =========================================
                    EDIT PRODUCT FORM
                    ========================================= --}}

                    <form method="POST" action="{{ route(
    'admin.products.update',
    $product->id
) }}" enctype="multipart/form-data" data-confirm data-confirm-type="edit"
                        data-confirm-title="Save Product Changes?"
                        data-confirm-message="Are you sure you want to update this product information?"
                        data-confirm-button="Save Changes">

                        @csrf

                        @method('PUT')



                        {{-- =====================================
                        SHARED PRODUCT FORM FIELDS
                        ===================================== --}}

                        @include(
                            'admin.products._form'
                        )



                        {{-- =====================================
                        FORM ACTIONS
                        ===================================== --}}

                        <div class="form-actions">


                            <a href="{{ route(
    'admin.products.show',
    $product->id
) }}" class="cancel-button">
                                Cancel
                            </a>


                            <button type="submit" class="primary-button">
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