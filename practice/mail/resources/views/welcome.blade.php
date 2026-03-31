<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Form</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-white bg-primary rounded p-2">Send Email</h5>
                        <form action="{{ route('sendEmail') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Subject</label>
                                <input type="text" class="form-control" name="subject" placeholder="Enter Subject">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Enter Message</label>
                                <textarea class="form-control" name="message" rows="4" placeholder="Enter Message"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Enter File</label>
                                <input type="file" class="form-control" name="attachment">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>