@extends('dashboards.manageAccess')


@section('permission-data')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Permission <a href="{{ route('permissions.index') }}" class="btn btn-danger float-end">Back</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('permissions.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Permission Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter Name">
                                 @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection