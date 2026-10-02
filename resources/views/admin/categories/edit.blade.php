<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Lumière | Edit Category
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/admin/dashboard.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/admin/categories.css') }}"
    >
</head>


<body>

<div class="admin-layout">

    {{-- =====================================================
        SHARED SIDEBAR
    ====================================================== --}}
    @include('admin.partials.sidebar')


    <main class="main-content">

        {{-- =================================================
            TOPBAR
        ================================================== --}}
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

                <a
                    href="{{
                        route(
                            'admin.categories.show',
                            $category->id
                        )
                    }}"
                    class="secondary-button"
                >
                    ← Back to Category
                </a>

            </div>

        </header>


        <section class="dashboard-content">

            {{-- =================================================
                SUCCESS MESSAGE
            ================================================== --}}
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


            {{-- =================================================
                VALIDATION MESSAGE
            ================================================== --}}
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


            {{-- =================================================
                CATEGORY VISUAL
            ================================================== --}}
            @php

                $categoryImagePath = trim(
                    $category->image ?? ''
                );

                $categoryIconPath = trim(
                    $category->icon ?? ''
                );

                $categoryVisualUrl = null;
                $categoryVisualPath = null;
                $categoryVisualType = null;


                /*
                |--------------------------------------------------------------------------
                | UPLOADED CATEGORY IMAGE
                |--------------------------------------------------------------------------
                */
                if ($categoryImagePath !== '') {

                    $categoryVisualPath =
                        $categoryImagePath;

                    $categoryVisualType =
                        'image';


                    if (
                        str_starts_with(
                            $categoryImagePath,
                            'http://'
                        ) ||
                        str_starts_with(
                            $categoryImagePath,
                            'https://'
                        )
                    ) {

                        $categoryVisualUrl =
                            $categoryImagePath;

                    } elseif (
                        str_starts_with(
                            $categoryImagePath,
                            'assets/'
                        )
                    ) {

                        $categoryVisualUrl =
                            asset(
                                $categoryImagePath
                            );

                    } elseif (
                        str_starts_with(
                            $categoryImagePath,
                            'storage/'
                        )
                    ) {

                        $categoryVisualUrl =
                            asset(
                                $categoryImagePath
                            );

                    } elseif (
                        str_starts_with(
                            $categoryImagePath,
                            'categories/'
                        )
                    ) {

                        $categoryVisualUrl =
                            route(
                                'category.image',
                                [
                                    'filename' =>
                                        basename(
                                            $categoryImagePath
                                        )
                                ]
                            );

                    } else {

                        $categoryVisualUrl =
                            asset(
                                'storage/' .
                                $categoryImagePath
                            );

                    }

                }

                /*
                |--------------------------------------------------------------------------
                | SEEDED SVG CATEGORY ICON
                |--------------------------------------------------------------------------
                */
                elseif ($categoryIconPath !== '') {

                    $categoryVisualPath =
                        $categoryIconPath;

                    $categoryVisualType =
                        'icon';


                    if (
                        str_starts_with(
                            $categoryIconPath,
                            'http://'
                        ) ||
                        str_starts_with(
                            $categoryIconPath,
                            'https://'
                        )
                    ) {

                        $categoryVisualUrl =
                            $categoryIconPath;

                    } else {

                        $categoryVisualUrl =
                            asset(
                                $categoryIconPath
                            );

                    }

                }

            @endphp


            <div class="category-form-card">

                {{-- =================================================
                    FORM HEADER
                ================================================== --}}
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


                    <div
                        class="form-header-icon"
                        style="
                            overflow: hidden;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        "
                    >

                        @if($categoryVisualUrl)

                            <img
                                src="{{ $categoryVisualUrl }}"
                                alt="{{ $category->name }}"
                                style="
                                    width: 100%;
                                    height: 100%;
                                    object-fit: contain;
                                    border-radius: 10px;
                                    padding: 5px;
                                    box-sizing: border-box;
                                "
                            >

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


                {{-- =================================================
                    SUMMARY
                ================================================== --}}
                <div
                    class="category-edit-summary"
                    style="
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 14px;
                        padding: 20px 28px;
                        border-bottom: 1px solid #f0e2e6;
                    "
                >

                    {{-- CURRENT CATEGORY --}}
                    <div
                        style="
                            padding: 14px 16px;
                            border: 1px solid #f0e2e6;
                            border-radius: 12px;
                            background: #fffafb;
                            display: flex;
                            flex-direction: column;
                            gap: 5px;
                        "
                    >

                        <span
                            style="
                                color: #9a8c91;
                                font-size: 11px;
                            "
                        >
                            Current Category
                        </span>

                        <strong
                            style="
                                color: #33282c;
                                font-size: 15px;
                            "
                        >
                            {{ $category->name }}
                        </strong>

                    </div>


                    {{-- ASSIGNED PRODUCTS --}}
                    <div
                        style="
                            padding: 14px 16px;
                            border: 1px solid #f0e2e6;
                            border-radius: 12px;
                            background: #fffafb;
                            display: flex;
                            flex-direction: column;
                            gap: 5px;
                        "
                    >

                        <span
                            style="
                                color: #9a8c91;
                                font-size: 11px;
                            "
                        >
                            Assigned Products
                        </span>

                        <strong
                            style="
                                color: #33282c;
                                font-size: 15px;
                            "
                        >
                            {{
                                number_format(
                                    $category->products_count
                                )
                            }}
                        </strong>

                    </div>


                    {{-- DELETE STATUS --}}
                    <div
                        style="
                            padding: 14px 16px;
                            border: 1px solid #f0e2e6;
                            border-radius: 12px;
                            background: #fffafb;
                            display: flex;
                            flex-direction: column;
                            gap: 5px;
                        "
                    >

                        <span
                            style="
                                color: #9a8c91;
                                font-size: 11px;
                            "
                        >
                            Delete Status
                        </span>


                        @if(
                            $category->products_count > 0
                        )

                            <strong
                                class="status-protected"
                                style="
                                    color: #b77a12;
                                    font-size: 15px;
                                "
                            >
                                Protected
                            </strong>

                        @else

                            <strong
                                class="status-safe"
                                style="
                                    color: #2f9259;
                                    font-size: 15px;
                                "
                            >
                                Can Be Deleted
                            </strong>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    UPDATE FORM
                ================================================== --}}
                <form
                    method="POST"
                    action="{{
                        route(
                            'admin.categories.update',
                            $category->id
                        )
                    }}"
                    enctype="multipart/form-data"
                    data-confirm
                    data-confirm-type="save"
                    data-confirm-title="Save Category Changes?"
                    data-confirm-message="Are you sure you want to update this category information?"
                    data-confirm-button="Save Changes"
                >

                    @csrf

                    @method('PUT')


                    @include(
                        'admin.categories._form',
                        [
                            'category' => $category
                        ]
                    )


                    <div class="category-form-actions">

                        <a
                            href="{{
                                route(
                                    'admin.categories.show',
                                    $category->id
                                )
                            }}"
                            class="cancel-button"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="save-category-button"
                        >

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