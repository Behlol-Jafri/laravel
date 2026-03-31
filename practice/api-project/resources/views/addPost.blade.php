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
                        <h5 class="bg-primary text-white p-2 rounded">Add Post</h5>
                    </div>
                    <div class="card-body">
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Title</label>
                                <input type="text" id="title" class="form-control" placeholder="Enter Title">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Description</label>
                                <textarea rows="3" type="text" id="description" class="form-control" placeholder="Enter Description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Image</label>
                                <input type="file" id="image" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button onclick="handleCancle()" class="btn btn-secondary me-3">Cancle</button>
                                <button onclick="handleSubmit()" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


    <script>
        function handleCancle(){
            window.location.href = '/post'
        }
        function handleSubmit(){
            const title = document.querySelector("#title");
            const description = document.querySelector("#description");
            const image = document.querySelector("#image");
            const token = localStorage.getItem('token');
            const formData =new FormData(); 
               formData.append('title',title.value);
               formData.append('description',description.value);
               formData.append('image',image.files[0]);
            

            fetch('/api/posts',{
                method:'POST',
                headers: {
                   'Authorization': `Bearer ${token}`,
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
               console.log('data = ',data)
                window.location.href = '/post'
            });
        }

    </script>
</body>
</html>