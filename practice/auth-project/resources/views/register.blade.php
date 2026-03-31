<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Authentication</title>
</head>
<body>

    <div class="container">
        <div class="row ">
            <div class="col-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-heading">Register</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('registerForm') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Name">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter Email">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Name</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter Name">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Enter Confirm Password">
                            </div>
                            <div class="my-3">
                                <button type="submit" class="btn btn-primary me-3">Submit</button>
                                <a href="/" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>