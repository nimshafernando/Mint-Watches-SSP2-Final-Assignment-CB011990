<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="mt-5 row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="text-center card-header">
                        Access Denied
                    </div>

                    <div class="text-center card-body">
                        <h5>You cannot access this page. Only admins can.</h5>
                        <a href="{{ url('/') }}" class="mt-4 btn btn-primary">Go to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
