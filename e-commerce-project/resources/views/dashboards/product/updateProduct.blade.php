@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
         <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2">Update Product</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.update', $product->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="" class="form-label">Title</label>
                                <input type="text" name="title" value="{{ $product->title }}" class="form-control" placeholder="Title">
                                 @error('title')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Description</label>
                                <input type="text" name="description" value="{{ $product->description }}" class="form-control" placeholder="Description">
                                 @error('description')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 d-flex justify-content-between gap-3">
                                <div class="w-50">
                                    <label for="" class="form-label">Price</label>
                                    <input type="number" name="price" value="{{ $product->getRawOriginal('price') }}" class="form-control" placeholder="Price">
                                    @error('price')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="w-50">
                                <label for="" class="form-label">Quantity</label>
                                <input type="number" name="quantity" value="{{ $product->quantity }}" class="form-control" placeholder="Quantity">
                                 @error('quantity')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            </div>
                            <div class="mb-3 d-flex justify-content-between gap-3">
                                 <div class=" w-50">
                                    <label for="" class="form-label">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category )
                                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->title }}</option> 
                                        @endforeach
                                    </select>
                                    @error('category')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class=" w-50">
                                    <label for="" class="form-label">SubCategory</label>
                                    <select name="subCategory" class="form-select">
                                        <option value="">Select SubCategory</option>
                                        @foreach ($subCategories as $subCategory )
                                        <option value="{{ $subCategory->id }}" {{ $subCategory->id == $product->subCategory_id ? 'selected' : '' }}>{{ $subCategory->title }}</option> 
                                        @endforeach
                                    </select>
                                    @error('subCategory')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            @if($product->images->count())
                                <div class="mb-3 d-flex justify-content-start gap-5">
                                    @foreach($product->images as $image)
                                        <div class="w-25 position-relative">
                                            <img src="{{ asset($image->image) }}" class="w-100 rounded">
                                            <a href="{{ route('deleteImage', $image->id) }}" class="btn btn-danger rounded-circle position-absolute top-0 end-0"><i class="fa-solid fa-trash  "></i></a>
                                        </div>
                                        
                                    @endforeach
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" id="images" class="form-control" name="images[]" multiple accept="image/*">
                                @error('images')<span class="text-danger">{{ $message }}</span>@enderror
                                @error('images.*')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div id="preview" class="mb-3" style="display:flex; gap:10px; flex-wrap:wrap;"></div>
                            <div class="">
                                <a href="{{ route('product.index') }}" class="btn btn-secondary me-3">Cancle</a>
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
    </div>


    <script>
let selectedFiles = [];

function updateInputFiles() {
    let dataTransfer = new DataTransfer();

    selectedFiles.forEach(file => {
        dataTransfer.items.add(file);
    });

    document.getElementById('images').files = dataTransfer.files;
}

document.getElementById('images').addEventListener('change', function(e) {
    let newFiles = Array.from(e.target.files);

    // Add new files to existing array
    selectedFiles = selectedFiles.concat(newFiles);
    updateInputFiles();
    showPreview();
});

function showPreview() {
    let preview = document.getElementById('preview');
    preview.innerHTML = "";

    selectedFiles.forEach((file, index) => {
        let reader = new FileReader();

        reader.onload = function(e) {
            let div = document.createElement('div');
            div.style.position = "relative";

            let img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = "100px";
            img.style.height = "100px";
            img.style.objectFit = "cover";

            // Remove button ❌
            let btn = document.createElement('button');
            btn.innerHTML = "X";
            btn.style.position = "absolute";
            btn.style.top = "0";
            btn.style.right = "0";
            btn.onclick = function() {
                selectedFiles.splice(index, 1);
                updateInputFiles();
                showPreview();
            };

            div.appendChild(img);
            div.appendChild(btn);
            preview.appendChild(div);
        };

        reader.readAsDataURL(file);
    });
}
</script>
@endsection