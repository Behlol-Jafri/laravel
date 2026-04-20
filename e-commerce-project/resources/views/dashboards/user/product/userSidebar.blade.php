    <label for="" class="form-label fw-semibold">Categories</label>
    @foreach ($categories as $category)
        <label class="d-block ms-3">
            <input type="checkbox" value="{{ $category->title }}">
            {{ $category->title }}
        </label>
    @endforeach  
    
     <label for="" class="form-label fw-semibold">Sub Categories</label>
    @foreach ($subCategories as $subCategory)
        <label class="d-block ms-3">
            <input type="checkbox" value="{{ $subCategory->title }}">
            {{ $subCategory->title }}
        </label>
    @endforeach  

   