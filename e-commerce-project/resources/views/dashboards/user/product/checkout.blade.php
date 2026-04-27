@extends('dashboards.user.userDashboard')

@section('content')
     <div class="container">
        <div class="row">
            <div class="col-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2">Checkout</h6>
                    </div>
                    <div class="card-body">
                        <form id="checkoutForm" method="post">
                            @csrf
                            <div class="mb-3 d-flex justify-content-between gap-3">
                                <div class="w-50">
                                <label for="" class="form-label">First Name</label>
                                <input type="text" name="firstName" value="{{ old('firstName') }}" class="form-control" placeholder="First Name">
                                 @error('firstName')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="w-50">
                                <label for="" class="form-label">Second Name</label>
                                <input type="text" name="secondName" value="{{ old('secondName') }}" class="form-control" placeholder="Second Name">
                                 @error('secondName')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email">
                                 @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 d-flex justify-content-between gap-3">
                                <div class="w-50">
                                    <label for="" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="w-50">
                                    <label for="" class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" value="{{ old('dob') }}" class="form-control">
                                    @error('dob')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Phone Number</label>
                                <input type="number" name="phoneNumber" value="{{ old('phoneNumber') }}" class="form-control" placeholder="Phone Number">
                                 @error('phoneNumber')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Address</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control" placeholder="Address">
                                 @error('address')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-primary">submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    let formData = {
        firstName: this.firstName.value,
        secondName: this.secondName.value,
        email: this.email.value,
        password: this.password.value,
        dob: this.dob.value,
        phoneNumber: this.phoneNumber.value,
        address: this.address.value,
        cart: cart
    };

    fetch("/checkout", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        localStorage.removeItem('cart');
        updateCartCount();
        updateOrderCount();

        window.location.href = "/orderDetails";
    });
});
</script>
@endsection