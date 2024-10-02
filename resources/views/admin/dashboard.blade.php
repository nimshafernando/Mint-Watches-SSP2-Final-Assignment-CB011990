<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            margin-bottom: 30px;
            color: #343a40;
            text-align: center;
        }

        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .card-header {
            background-color: #343a40;
            color: white;
            padding: 10px;
        }

        .card-body ul {
            padding-left: 0;
            list-style: none;
        }

        .card-body ul li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body ul li:last-child {
            border-bottom: none;
        }

        .btn-success {
            margin-right: 10px;
        }

        .btn-decline {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>

        <!-- Pending Products -->
        <div class="card">
            <div class="card-header">
                Pending Products
            </div>
            <div class="card-body">
                @if($pendingProducts->isEmpty())
                    <p>No pending products.</p>
                @else
                    <ul>
                        @foreach($pendingProducts as $product)
                            <li>
                                <div>
                                    <strong>{{ $product->name }}</strong> ({{ $product->price }} USD)
                                </div>
                                <div>
                                    <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.products.decline', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-decline btn-sm">Decline</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Approved Products -->
        <div class="card">
            <div class="card-header">
                Approved Products
            </div>
            <div class="card-body">
                @if($approvedProducts->isEmpty())
                    <p>No approved products.</p>
                @else
                    <ul>
                        @foreach($approvedProducts as $product)
                            <li><strong>{{ $product->name }}</strong> ({{ $product->price }} USD)</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Declined Products -->
        <div class="card">
            <div class="card-header">
                Declined Products
            </div>
            <div class="card-body">
                @if($declinedProducts->isEmpty())
                    <p>No declined products.</p>
                @else
                    <ul>
                        @foreach($declinedProducts as $product)
                            <li><strong>{{ $product->name }}</strong> ({{ $product->price }} USD)</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
