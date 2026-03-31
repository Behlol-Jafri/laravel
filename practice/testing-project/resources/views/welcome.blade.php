<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Form</title>
</head>
<body>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-5">
                <form action={{'/addUser'}} method="POST" class="rounded border p-2">
        @csrf
        <h1 class="bg-primary text-white fs-4  p-2 rounded mb-2">Add Users</h1>
        <div>
            <label class="px-2 form-label my-2">Enter Name</label>
            <input 
                type="text" 
                placeholder="Enter Name"
                name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror"
            >
            <span class="text-danger">
                @error('name')
                    {{ $message }}
                @enderror
            </span>
        </div>
        <div>
            <label class="form-label px-2 my-2">Enter Email</label>
            <input 
                type="email" 
                placeholder="Enter Email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
            >
            <span class="text-danger">
                @error('email')
                    {{ $message }}
                @enderror
            </span>
        </div>
        <div>
            <label class="form-label px-2 my-2">Enter Password</label>
            <input 
                type="number" 
                placeholder="Enter Password"
                name="password"
                value="{{ old('password') }}"
                class="form-control @error('password') is-invalid @enderror"
            >
            <span class="text-danger">
                @error('password')
                    {{ $message }}
                @enderror
            </span>
        </div>
        <div class="text-end">
            <button class="btn btn-primary my-2" type="submit">Sumbit</button>
        </div>
    </form>
            </div>
        </div>
    </div>


    



</body>
</html>