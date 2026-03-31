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
                        <h2 class="card-heading">Welcome, {{ Auth::user()->name }}</h2>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('inner') }}" class="btn btn-primary me-3">Go to inner page</a>
                        <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>