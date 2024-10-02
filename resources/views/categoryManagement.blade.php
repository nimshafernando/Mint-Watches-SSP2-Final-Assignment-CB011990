<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
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
    </style>
</head>

<body class="bg-gray-100">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="p-6 text-center">Admin Dashboard</h2>
        <a href="{{ route('dashboard') }}" class="block px-6 py-3 hover:bg-gray-700">Moderation</a>
        <a href="{{ route('analytics') }}" class="block px-6 py-3 hover:bg-gray-700">Analytics</a>
        <a href="{{ route('userManagement') }}" class="block px-6 py-3 hover:bg-gray-700">User Management</a>
        <a href="{{ route('categoryManagement') }}" class="block px-6 py-3 hover:bg-gray-700">Category Management</a>

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
        <h1 class="mb-6 text-3xl font-bold text-center">Category Management</h1>

        <!-- Add New Category -->
        <div class="p-6 mb-6 bg-white rounded-lg shadow-lg">
            <h2 class="text-xl font-bold">Add New Category</h2>
            <form action="{{ route('category.add') }}" method="POST">
                @csrf
                <div class="mt-4">
                    <input type="text" name="category" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Enter new category" required>
                </div>
                <button type="submit" class="px-4 py-2 mt-4 text-white bg-blue-500 rounded hover:bg-blue-600">Add Category</button>
            </form>
        </div>

        <!-- List of Existing Categories -->
        <div class="p-6 bg-white rounded-lg shadow-lg">
            <h2 class="text-xl font-bold">Existing Categories</h2>
            <div class="mt-4 space-y-4">
                @forelse($categories as $category)
                <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg">
                    <span>{{ $category->category }}</span>
                    <form action="{{ route('category.remove', $category->category) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
                    </form>
                </div>
                @empty
                <p>No categories found.</p>
                @endforelse
            </div>
        </div>
    </div>

</body>

</html>
