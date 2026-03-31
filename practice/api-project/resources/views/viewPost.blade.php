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
                        <h5 class="bg-primary text-white p-2 rounded">View Post</h5>
                    </div>
                    <div class="card-body">
                            <div class="mb-3">
                                <h6 class="d-inline">Title : </h6>
                            <p class="d-inline" id="title"></p>
                            </div>
                            <div class="mb-3">
                                <h6 class="d-inline">Description : </h6>
                            <p class="d-inline" id="description"></p>
                            </div>
                            <div class="">
                                <img width="100px" id="image" class="rounded mb-3" alt="">
                            </div>
                            <div class="mb-3">
                                <button onclick="handleCancle()" class="btn btn-secondary">Back</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


    <script>

        function handleShowPost(){
            const id = localStorage.getItem('id');
            const token = localStorage.getItem('token');

            fetch(`/api/posts/${id}`,{
                method:'GET',
                headers: {
                   'Authorization': `Bearer ${token}`,
                },
            })
            .then(response => response.json())
            .then(data => {
               console.log('data = ',data)
               const title = document.querySelector("#title");
               const description = document.querySelector("#description");
               const image = document.querySelector("#image");
               title.innerText = data.data.title;
               description.innerText = data.data.description;
               image.src = `/uploads/${data.data.image}`;
            });
        }
        handleShowPost();
        function handleCancle(){
            localStorage.removeItem('id');
            window.location.href = '/post'
        }
        

    </script>
</body>
</html>