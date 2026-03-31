<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ajax CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="bg-primary text-white p-2 rounded">Login</h5>
                    </div>
                    <div class="card-body">
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Email</label>
                                <input type="email" id="email" class="form-control" placeholder="Enter Email">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Password</label>
                                <input type="password" id="password" class="form-control" placeholder="Enter Password">
                            </div>
                            <div class="mb-3">
                                <button onclick="handleLogin()" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


    <script>

        function handleLogin(){
            const email = document.querySelector("#email");
            const password = document.querySelector("#password");

            const data = {
                'email':email.value,
                'password':password.value,
            }

            fetch('/api/login',{
                method:'POST',
                headers: {
                   'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                localStorage.setItem('token', data.token);
                window.location.href = '/post'
            });
        }

    </script>
</body>
</html>