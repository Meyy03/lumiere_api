<div class="product-form-grid">

    {{-- CATEGORY --}}
    <div class="form-group">
        <label for="category_id">
            Category <span>*</span>
        </label>

        <select
            id="category_id"
            name="category_id"
            class="@error('category_id') field-invalid @enderror"
        >
            <option value="">Select category</option>

            @foreach($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ (string) old('category_id', $product->category_id ?? '') === (string) $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        @error('category_id')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- TARGET AUDIENCE --}}
    <div class="form-group">
        <label for="target_audience">
            Shopping Type / Target Audience <span>*</span>
        </label>

        <select
            id="target_audience"
            name="target_audience"
            class="@error('target_audience') field-invalid @enderror"
        >
            <option value="">
                Select target audience
            </option>

            <option
                value="female"
                {{ old('target_audience', $product->target_audience ?? 'all') === 'female' ? 'selected' : '' }}
            >
                Women
            </option>

            <option
                value="male"
                {{ old('target_audience', $product->target_audience ?? 'all') === 'male' ? 'selected' : '' }}
            >
                Men
            </option>

            <option
                value="all"
                {{ old('target_audience', $product->target_audience ?? 'all') === 'all' ? 'selected' : '' }}
            >
                All / Unisex
            </option>
        </select>

        <small class="field-hint">
            Choose who this product is mainly recommended for.
        </small>

        @error('target_audience')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- PRODUCT NAME --}}
    <div class="form-group">
        <label for="name">
            Product Name <span>*</span>
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $product->name ?? '') }}"
            placeholder="Example: Glow Serum"
            class="@error('name') field-invalid @enderror"
        >

        @error('name')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- DEFAULT SIZE --}}
    <div class="form-group">
        <label for="size">
            Default Size <span>*</span>
        </label>

        <input
            type="text"
            id="size"
            name="size"
            value="{{ old('size', $product->size ?? '') }}"
            placeholder="Example: 100 ml"
            class="@error('size') field-invalid @enderror"
        >

        @error('size')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- AVAILABLE SIZES --}}
    <div class="form-group">
        <label for="available_sizes">
            Available Sizes
        </label>

        <input
            type="text"
            id="available_sizes"
            name="available_sizes"
            value="{{ old('available_sizes', $product->available_sizes_text ?? '') }}"
            placeholder="50 ml, 100 ml, 200 ml"
            class="@error('available_sizes') field-invalid @enderror"
        >

        <small class="field-hint">
            Separate each size with a comma.
        </small>

        @error('available_sizes')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- PRICE --}}
    <div class="form-group">
        <label for="price">
            Price ($) <span>*</span>
        </label>

        <input
            type="number"
            id="price"
            name="price"
            value="{{ old('price', $product->price ?? '') }}"
            min="0.01"
            step="0.01"
            placeholder="18.00"
            class="@error('price') field-invalid @enderror"
        >

        @error('price')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- STOCK --}}
    <div class="form-group">
        <label for="stock">
            Stock <span>*</span>
        </label>

        <input
            type="number"
            id="stock"
            name="stock"
            value="{{ old('stock', $product->stock ?? 0) }}"
            min="0"
            step="1"
            class="@error('stock') field-invalid @enderror"
        >

        @error('stock')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- RATING --}}
    <div class="form-group">
        <label for="rating">
            Rating
        </label>

        <input
            type="number"
            id="rating"
            name="rating"
            value="{{ old('rating', $product->rating ?? 0) }}"
            min="0"
            max="5"
            step="0.1"
            class="@error('rating') field-invalid @enderror"
        >

        <small class="field-hint">
            Rating must be between 0 and 5.
        </small>

        @error('rating')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- SKIN TYPE --}}
    <div class="form-group">
        <label for="skin_type">
            Skin Type <span>*</span>
        </label>

        <input
            type="text"
            id="skin_type"
            name="skin_type"
            value="{{ old('skin_type', $product->skin_type ?? 'Suitable for all skin types') }}"
            placeholder="Suitable for all skin types"
            class="@error('skin_type') field-invalid @enderror"
        >

        @error('skin_type')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- PRODUCT IMAGE --}}
    <div class="form-group full-width">
        <label class="product-image-label">
            Product Image

            @if(!isset($product))
                <span>*</span>
            @endif
        </label>

        <div class="product-image-upload-area">

            {{-- Hidden real file input --}}
            <input
                type="file"
                id="image_file"
                name="image_file"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                class="hidden-image-input"
            >


            {{-- Clickable circular image picker --}}
            <label
                for="image_file"
                class="circle-image-picker @error('image_file') circle-image-error @enderror"
            >
                <img
                    id="imagePreview"
                    class="circle-image-preview"
                    src=""
                    alt="Product image preview"
                    style="display: none;"
                >

                <div
                    class="circle-upload-placeholder"
                    id="imagePlaceholder"
                >
                    <div class="upload-plus">
                        +
                    </div>

                    <strong>
                        Add Image
                    </strong>

                    <span>
                        Click to choose
                    </span>
                </div>
            </label>


            <label
                for="image_file"
                class="change-image-button"
                id="changeImageButton"
                style="display: none;"
            >
                Change Image
            </label>


            <p
                class="selected-file-name"
                id="selectedFileName"
            ></p>


            @if(
                isset($product) &&
                !empty($product->image) &&
                !str_starts_with($product->image, 'products/')
            )
                <div class="legacy-image-info">

                    <strong>
                        Current Flutter Asset
                    </strong>

                    <span>
                        {{ $product->image }}
                    </span>

                    <small>
                        This image stays unchanged unless you select a new image.
                    </small>

                </div>
            @endif


            <small class="field-hint image-help">
                JPG, JPEG, PNG or WEBP
                <span>•</span>
                Maximum 5 MB
            </small>


            @if(isset($product))
                <small class="field-hint">
                    Leave unchanged to keep the current image.
                </small>
            @endif


            @error('image_file')
                <small class="validation-error image-validation-error">
                    {{ $message }}
                </small>
            @enderror

        </div>
    </div>


    {{-- DESCRIPTION --}}
    <div class="form-group full-width">
        <label for="description">
            Description
        </label>

        <textarea
            id="description"
            name="description"
            rows="4"
            placeholder="Product description..."
            class="@error('description') field-invalid @enderror"
        >{{ old('description', $product->description ?? '') }}</textarea>

        @error('description')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- PRODUCT DETAILS --}}
    <div class="form-group full-width">
        <label for="product_details">
            Product Details
        </label>

        <textarea
            id="product_details"
            name="product_details"
            rows="4"
            placeholder="Product details..."
            class="@error('product_details') field-invalid @enderror"
        >{{ old('product_details', $product->product_details ?? '') }}</textarea>

        @error('product_details')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- INGREDIENTS --}}
    <div class="form-group full-width">
        <label for="ingredients">
            Ingredients
        </label>

        <textarea
            id="ingredients"
            name="ingredients"
            rows="4"
            placeholder="Product ingredients..."
            class="@error('ingredients') field-invalid @enderror"
        >{{ old('ingredients', $product->ingredients ?? '') }}</textarea>

        @error('ingredients')
            <small class="validation-error">
                {{ $message }}
            </small>
        @enderror
    </div>


    {{-- ACTIVE --}}
    <div class="form-group full-width">
        <label class="switch-row">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                {{ old('is_active', $product->is_active ?? 1) ? 'checked' : '' }}
            >

            <span>
                Active Product
            </span>

        </label>

        <small class="field-hint">
            Inactive products remain in the database.
        </small>
    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageInput =
        document.getElementById('image_file');

    const imagePreview =
        document.getElementById('imagePreview');

    const imagePlaceholder =
        document.getElementById('imagePlaceholder');

    const changeImageButton =
        document.getElementById('changeImageButton');

    const selectedFileName =
        document.getElementById('selectedFileName');


    @if(
        isset($product) &&
        !empty($product->image) &&
        str_starts_with($product->image, 'products/')
    )

        imagePreview.src =
            "{{ asset('storage/' . $product->image) }}";

        imagePreview.style.display =
            'block';

        imagePlaceholder.style.display =
            'none';

        changeImageButton.style.display =
            'inline-flex';

    @endif


    imageInput.addEventListener(
        'change',
        function () {

            const file =
                this.files[0];


            if (!file) {
                return;
            }


            selectedFileName.textContent =
                file.name;


            const reader =
                new FileReader();


            reader.onload = function (event) {

                imagePreview.src =
                    event.target.result;

                imagePreview.style.display =
                    'block';

                imagePlaceholder.style.display =
                    'none';

                changeImageButton.style.display =
                    'inline-flex';
            };


            reader.readAsDataURL(file);
        }
    );

});
</script>