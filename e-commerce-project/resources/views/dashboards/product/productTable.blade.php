 @if($products->total() > 0)   
    @foreach ($products as $index => $product)
        <tr class="
           @if (Auth::user()->hasRole('Vender'))
                @if($product->review_status == 'rejected' && !$product->is_read)
                    table-danger
                @elseif($product->review_status == 'approved' && !$product->is_read)
                    table-success
                @endif
           @endif
        ">
            <td>{{ $index+1 }}</td>
            <td style="width: 50px">
                @if($product->images->count() > 0)
                    <img src="{{ asset($product->images->first()->image) }}" class="rounded w-100">
                @else
                    No Image
                @endif
            </td>
            <td>{{ $product->title }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->user_id }}</td>
            <td>{{ $product->category_id }}</td>
            <td>{{ $product->subCategory_id }}</td>
            <td class="text-center"><a href="{{ route('product.show', $product->id) }}" class="btn btn-success"><i class="fa-solid fa-eye fa-sm"></i></a></td>
            @if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'))
                @if($product->review_status == 'pending')
                    <td class="text-center"><a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $product->id }}">Review</a></td>
                @endif
                @if($product->review_status != 'pending')
                    <td class="text-center"><a class="btn btn-warning">Reviewed</a></td>
                @endif
            @endif
            @if (Auth::user()->hasRole('Vender'))
                @if(!$product->is_read && $product->review_status != 'pending')
                    <td class="text-center"><a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewReview{{ $product->id }}">View Review</a></td>
                @endif
            @endif
            @can('update product')
                <td class="text-center"><a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square fa-sm"></i></a></td>
            @endcan
            @can('delete product')
                <td class="text-center">
                <form action="{{ route('product.destroy', $product->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger"><i class="fa-solid fa-trash fa-sm"></i></button>
                </form>
            </td>
            @endcan
        </tr>


        <div class="modal fade" id="reviewModal{{ $product->id }}">
    <div class="modal-dialog">
        <form action="{{ route('review', $product->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Review Product</h5>
                </div>

                <div class="modal-body">
                    <textarea name="admin_message" class="form-control" placeholder="Write message"></textarea>

                    <select name="review_status" class="form-control mt-2">
                        <option value="approved">Approve</option>
                        <option value="rejected">Reject</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Send Review</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="viewReview{{ $product->id }}">
    <div class="modal-dialog">
        <form action="{{ route('reviewRead', $product->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Admin Message</h5>
                </div>

                <div class="modal-body">
                    <p>{{ $product->admin_message }}</p>

                    <label>
                        <input type="checkbox" name="is_read" value="1" required>
                        OK
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
    @endforeach

@else 
    <tr>
        <td colspan="11" class="text-center text-danger">
            Products Not Found
        </td>
    </tr>
@endif