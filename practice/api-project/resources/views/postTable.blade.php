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
            <div class="col-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-white bg-primary rounded p-2">Post Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <button class="btn btn-primary me-3" onclick="addPost()">Add Post</button>
                            <button class="btn btn-danger" onclick="handleLogout()">Logout</button>
                        </div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th width='75px'>Image</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th colspan="3" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tblBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


    <script>
        function addPost(){
            window.location.href = '/addPost'
        }
        function handleLogout(){
            const token = localStorage.getItem('token');
            fetch('/api/logout',{
                method:'POST',
                headers: {
                   'Authorization': `Bearer ${token}`,
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log('data = ',data);
                window.location.href = '/'
            });
        }
        function onLoad(){
            const token = localStorage.getItem('token');
            fetch('/api/posts',{
                method:'GET',
                headers: {
                   'Authorization': `Bearer ${token}`,
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log('data = ',data);
                const allData = data.data;
                const tblBody = document.querySelector('#tblBody');
                let tr = '';
                allData.forEach(post => {
                    tr += `<tr>
                                    <td>${post?.id}</td>
                                    <td >
                                        <img
                                        src="/uploads/${post?.image}"
                                        class="w-100 rounded"
                                        alt=""
                                    />
                                     </td>
                                    <td>${post?.title}</td>
                                    <td>${post?.description}</td>
                                    <td><button class="btn btn-success" onclick="showSinglePost(${post?.id})">Veiw</button></td>
                                    <td><button class="btn btn-warning" onclick="updatePost(${post?.id})">Update</button></td>
                                    <td><button class="btn btn-danger" onclick="deletePost(${post?.id})">Delete</button></td>
                                </tr>`;
                    
                });
                tblBody.innerHTML = tr;
            });
        }
        onLoad()

        function showSinglePost(id){
            localStorage.setItem('id',id);
            window.location.href = `/viewPost/${id}`
        }
        function updatePost(id){
             localStorage.setItem('id',id);
            window.location.href = `/updatePost/${id}`
        }
        function deletePost(id){
            console.log('id = ',id);
            const token = localStorage.getItem('token');

            fetch(`/api/posts/${id}`,{
                method:'DELETE',
                headers: {
                   'Authorization': `Bearer ${token}`,
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log("data = ",data);
                onLoad();
            });
        }
    </script>
</body>
</html>