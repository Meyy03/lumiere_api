@php

    /*
    |--------------------------------------------------------------------------
    | CATEGORY STATE
    |--------------------------------------------------------------------------
    */

    $categoryExists =
        isset($category) &&
        $category->exists;


    /*
    |--------------------------------------------------------------------------
    | EXISTING CATEGORY IMAGE / ICON
    |--------------------------------------------------------------------------
    */

    $existingImagePath =
        trim(
            $category->image ?? ''
        );

    $existingIconPath =
        trim(
            $category->icon ?? ''
        );


    $existingVisualUrl = null;
    $existingVisualPath = null;
    $existingVisualType = null;


    /*
    |--------------------------------------------------------------------------
    | UPLOADED CATEGORY IMAGE
    |--------------------------------------------------------------------------
    */

    if ($existingImagePath !== '') {

        $existingVisualPath =
            $existingImagePath;

        $existingVisualType =
            'image';


        if (
            str_starts_with(
                $existingImagePath,
                'http://'
            ) ||
            str_starts_with(
                $existingImagePath,
                'https://'
            )
        ) {

            $existingVisualUrl =
                $existingImagePath;

        } elseif (
            str_starts_with(
                $existingImagePath,
                'assets/'
            )
        ) {

            $existingVisualUrl =
                asset(
                    $existingImagePath
                );

        } elseif (
            str_starts_with(
                $existingImagePath,
                'storage/'
            )
        ) {

            $existingVisualUrl =
                asset(
                    $existingImagePath
                );

        } elseif (
            str_starts_with(
                $existingImagePath,
                'categories/'
            )
        ) {

            $existingVisualUrl =
                route(
                    'category.image',
                    [
                        'filename' =>
                            basename(
                                $existingImagePath
                            )
                    ]
                );

        } else {

            $existingVisualUrl =
                asset(
                    'storage/' .
                    $existingImagePath
                );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | SEEDED SVG ICON
    |--------------------------------------------------------------------------
    */

    elseif ($existingIconPath !== '') {

        $existingVisualPath =
            $existingIconPath;

        $existingVisualType =
            'icon';


        if (
            str_starts_with(
                $existingIconPath,
                'http://'
            ) ||
            str_starts_with(
                $existingIconPath,
                'https://'
            )
        ) {

            $existingVisualUrl =
                $existingIconPath;

        } else {

            $existingVisualUrl =
                asset(
                    $existingIconPath
                );

        }

    }

@endphp


<div class="category-form-grid">

    {{-- =====================================================
        CATEGORY NAME
    ====================================================== --}}
    <div class="form-group">

        <label for="name">

            Category Name

            <span class="required">*</span>

        </label>


        <input
            type="text"
            id="name"
            name="name"
            value="{{
                old(
                    'name',
                    $category->name ?? ''
                )
            }}"
            placeholder="Example: Hair Care"
            maxlength="100"
            autocomplete="off"
            required
        >


        @error('name')

            <div class="field-error">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =====================================================
        SLUG
    ====================================================== --}}
    <div class="form-group">

        <label for="slug">
            Slug
        </label>


        <input
            type="text"
            id="slug"
            name="slug"
            value="{{
                old(
                    'slug',
                    $category->slug ?? ''
                )
            }}"
            placeholder="Example: hair-care"
            maxlength="120"
            autocomplete="off"
        >


        <small class="form-help">

            Leave blank and Laravel will generate it
            automatically.

        </small>


        @error('slug')

            <div class="field-error">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =====================================================
        CATEGORY IMAGE
    ====================================================== --}}
    <div class="form-group form-group-full">

        <label>

            Category Image

            @if(!$categoryExists)

                <span class="required">*</span>

            @endif

        </label>


        <div class="category-image-section">

            {{-- =============================================
                CLICKABLE CIRCLE
            ============================================== --}}
            <label
                for="image"
                class="category-image-circle"
                id="categoryImageCircle"
                title="Click to choose category image"
            >

                {{-- EXISTING / NEW IMAGE PREVIEW --}}
                <img
                    id="categoryImagePreview"

                    @if($existingVisualUrl)

                        src="{{ $existingVisualUrl }}"

                    @else

                        src=""

                    @endif

                    alt="Category image preview"

                    class="
                        category-preview-image
                        {{
                            $existingVisualUrl
                                ? ''
                                : 'is-hidden'
                        }}
                    "

                    style="
                        object-fit: contain;
                        padding: 7px;
                        box-sizing: border-box;
                    "
                >


                {{-- PLACEHOLDER --}}
                <div
                    id="categoryImagePlaceholder"
                    class="
                        category-image-placeholder
                        {{
                            $existingVisualUrl
                                ? 'is-hidden'
                                : ''
                        }}
                    "
                >

                    {{-- CAMERA ICON --}}
                    <svg
                        viewBox="0 0 24 24"
                        class="category-upload-icon"
                        aria-hidden="true"
                    >

                        <path
                            d="
                                M4 7h3l1.5-2h7L17 7h3
                                a2 2 0 0 1 2 2v9
                                a2 2 0 0 1-2 2H4
                                a2 2 0 0 1-2-2V9
                                a2 2 0 0 1 2-2z
                            "
                        ></path>

                        <circle
                            cx="12"
                            cy="13"
                            r="4"
                        ></circle>

                    </svg>


                    <strong>
                        Add Image
                    </strong>

                    <span>
                        Click to upload
                    </span>

                </div>


                {{-- HOVER OVERLAY --}}
                <div class="category-image-overlay">

                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >

                        <path
                            d="
                                M4 7h3l1.5-2h7L17 7h3
                                a2 2 0 0 1 2 2v9
                                a2 2 0 0 1-2 2H4
                                a2 2 0 0 1-2-2V9
                                a2 2 0 0 1 2-2z
                            "
                        ></path>

                        <circle
                            cx="12"
                            cy="13"
                            r="4"
                        ></circle>

                    </svg>

                    <span>

                        {{
                            $existingVisualUrl
                                ? 'Change'
                                : 'Choose'
                        }}

                    </span>

                </div>

            </label>


            {{-- =============================================
                HIDDEN FILE INPUT
            ============================================== --}}
            <input
                type="file"
                id="image"
                name="image"
                accept="
                    image/jpeg,
                    image/png,
                    image/webp
                "
                class="category-hidden-file-input"
                {{ !$categoryExists ? 'required' : '' }}
            >


            {{-- =============================================
                IMAGE INFORMATION
            ============================================== --}}
            <div class="category-image-information">

                <strong id="categoryImageTitle">

                    @if($existingVisualType === 'image')

                        Current Category Image

                    @elseif($existingVisualType === 'icon')

                        Current Category Icon

                    @else

                        Upload Category Image

                    @endif

                </strong>


                <p id="categoryImageFileName">

                    @if($existingVisualPath)

                        {{ basename($existingVisualPath) }}

                    @else

                        Click the circle to choose an image.

                    @endif

                </p>


                <small>

                    JPG, JPEG, PNG or WEBP • Maximum 4 MB

                </small>


                @if(
                    $categoryExists &&
                    $existingVisualType === 'image'
                )

                    <span class="category-current-image-note">

                        Leave unchanged to keep the current image.

                    </span>

                @elseif(
                    $categoryExists &&
                    $existingVisualType === 'icon'
                )

                    <span class="category-current-image-note">

                        This category currently uses its seeded
                        Lumière SVG icon. Upload an image only if
                        you want to replace it with a custom image.

                    </span>

                @endif

            </div>

        </div>


        @error('image')

            <div class="field-error category-image-error">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =====================================================
        MOBILE APP INFORMATION
    ====================================================== --}}
    <div class="category-form-note form-group-full">

        <div class="note-icon">
            i
        </div>


        <div>

            <strong>
                Mobile App Image
            </strong>

            <p>

                The category visual will be displayed in the
                Lumière mobile application through the REST API.

            </p>

        </div>

    </div>

</div>


{{-- =========================================================
    CATEGORY IMAGE PREVIEW SCRIPT
========================================================= --}}
<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const imageInput =
            document.getElementById('image');

        const imagePreview =
            document.getElementById(
                'categoryImagePreview'
            );

        const imagePlaceholder =
            document.getElementById(
                'categoryImagePlaceholder'
            );

        const imageFileName =
            document.getElementById(
                'categoryImageFileName'
            );

        const imageTitle =
            document.getElementById(
                'categoryImageTitle'
            );


        if (!imageInput) {
            return;
        }


        imageInput.addEventListener(
            'change',
            function (event) {

                const file =
                    event.target.files[0];


                if (!file) {
                    return;
                }


                // ============================================
                // ALLOWED IMAGE TYPES
                // ============================================

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];


                if (
                    !allowedTypes.includes(
                        file.type
                    )
                ) {

                    alert(
                        'Please choose a JPG, JPEG, PNG or WEBP image.'
                    );

                    imageInput.value = '';

                    return;

                }


                // ============================================
                // MAXIMUM 4 MB
                // ============================================

                const maximumSize =
                    4 * 1024 * 1024;


                if (
                    file.size > maximumSize
                ) {

                    alert(
                        'Category image must not be larger than 4 MB.'
                    );

                    imageInput.value = '';

                    return;

                }


                // ============================================
                // PREVIEW SELECTED IMAGE
                // ============================================

                const reader =
                    new FileReader();


                reader.onload =
                    function (readerEvent) {

                        imagePreview.src =
                            readerEvent.target.result;

                        imagePreview.classList.remove(
                            'is-hidden'
                        );

                        imagePlaceholder.classList.add(
                            'is-hidden'
                        );


                        imageTitle.textContent =
                            'Selected Category Image';

                        imageFileName.textContent =
                            file.name;

                    };


                reader.readAsDataURL(
                    file
                );

            }
        );

    }
);

</script>