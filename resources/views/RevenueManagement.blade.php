<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Management</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fira Sans Font -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Fira Sans', sans-serif;
        }

        .custom-card {
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .custom-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            height: 100vh;
        }

        .sidebar a {
            padding: 15px 20px;
            display: block;
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .logout-button {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }

        h3 {
            text-align: center;
            font-size: 36px;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            margin-bottom: 1rem;
        }

        table th, table td {
            padding: 1rem;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="p-6 text-center">Admin Dashboard</h2>
        <a href="{{ route('dashboard') }}" class="block px-6 py-3 hover:bg-gray-700">Moderation</a>
        <a href="{{ route('analytics') }}" class="block px-6 py-3 hover:bg-gray-700">Analytics</a>
        <a href="{{ route('userManagement') }}" class="block px-6 py-3 hover:bg-gray-700">User Management</a>
        <a href="{{ route('categoryManagement') }}" class="block px-6 py-3 hover:bg-gray-700">Category Management</a>
        <a href="{{ route('revenue.management') }}" class="block px-6 py-3 bg-gray-800 hover:bg-gray-700">Revenue Management</a>

        <!-- Log Out -->
        <div class="logout-button">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="px-4 py-2 text-white bg-red-600 rounded shadow hover:bg-red-500">
                Log Out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 ml-64">
        <h3 class="mb-6 text-3xl font-semibold text-gray-800">Revenue Management</h3>

        <!-- Section: User Revenue Summary -->
        <div class="section-title">Total Revenue by Seller</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Seller</th>
                    <th>Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($userRevenues as $seller => $revenue)
                    <tr>
                        <td>{{ $seller }}</td>
                        <td>${{ number_format($revenue, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Section: Product List -->
        <div class="section-title">Product Details</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Payment Status</th>
                    <th>Update Payment Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->brand }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->category }}</td>
                        <td>{{ ucfirst($product->payment_status) }}</td>
                        <td>
                            <form action="{{ route('revenue.updatePaymentStatus', $product->id) }}" method="POST">
                                @csrf
                                <select name="payment_status" class="form-control">
                                    @foreach ($paymentStatuses as $status)
                                        <option value="{{ $status }}" {{ $product->payment_status == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                        @endforeach
                        </select>
                            <button type="submit" class="mt-2 btn btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </body>

</html>
