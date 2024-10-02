<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .popup-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            padding: 20px;
            border-radius: 8px;
            display: none;
        }

        .product-card {
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* This ensures the log out button goes to the bottom */
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

        .modal img {
            max-width: 80%;
            max-height: 80%;
        }

        .modal.active {
            display: flex;
        }
    </style>
</head>

<body class="bg-gray-100" x-data="{ showPopup: false, userName: '', userEmail: '', imageModal: false, modalImage: '' }">

    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <h2 class="p-6 text-center">Admin Dashboard</h2>
            <a href="{{ route('dashboard') }}" class="block px-6 py-3 hover:bg-gray-700">Moderation</a>
            <a href="{{ route('analytics') }}" class="block px-6 py-3 hover:bg-gray-700">Analytics</a>
            <a href="{{ route('userManagement') }}" class="block px-6 py-3 hover:bg-gray-700">User Management</a>
            <a href="{{ route('categoryManagement') }}" class="block px-6 py-3 hover:bg-gray-700">Catergory Management</a>
        </div>
        
        <!-- Log Out -->
        <div class="p-6 logout-button">
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
        <h1 class="mb-6 text-3xl font-bold text-center">User Management</h1>

        <!-- Top Cards Section -->
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
            <div class="flex items-center justify-center p-6 text-white bg-blue-500 rounded-lg shadow-lg">
                <i class="mr-4 fas fa-users fa-3x"></i>
                <div class="text-center">
                    <h3 class="text-lg font-bold">Total Users</h3>
                    <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                </div>
            </div>
            <div class="flex items-center justify-center p-6 text-white bg-green-500 rounded-lg shadow-lg">
                <i class="mr-4 fas fa-user-check fa-3x"></i>
                <div class="text-center">
                    <h3 class="text-lg font-bold">Active Users</h3>
                    <p class="text-3xl font-bold">{{ $activeUsers }}</p>
                </div>
            </div>
        </div>

        <!-- User List Section -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($users as $user)
            <div class="p-6 space-y-4 bg-white rounded-lg shadow-lg product-card">
                <div class="text-center">
                    <i class="text-gray-600 fas fa-user-circle fa-3x"></i>
                    <h4 class="mt-2 text-lg font-bold">{{ $user->name }}</h4>
                    <p class="text-sm text-gray-500">Last login: {{ $user->updated_at->format('Y-m-d H:i') }}</p>
                </div>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $user->phone_number }}</p>
                <p><strong>Location:</strong> {{ $user->location }}</p>

                <!-- User's Products -->
                <div>
                    <h5 class="font-bold text-gray-600">Products Posted:</h5>
                    @forelse($user->products as $product)
                    <div class="p-2 mt-2 text-center bg-gray-100 rounded shadow">
                        <p class="font-bold">{{ $product->name }}</p>
                        <p>{{ $product->description }}</p>
                        <!-- Centered Image Section with Modal on Click -->
                        <div class="grid grid-cols-1 justify-items-center">
                            @foreach(json_decode($product->images) as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Product Image" class="object-cover w-40 h-40 transition rounded cursor-pointer hover:opacity-75" @click="imageModal = true; modalImage = '{{ asset('storage/' . $image) }}'">
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Last updated: {{ $product->updated_at->format('Y-m-d H:i') }}</p>
                        <!-- Delete Product Button -->
                        <form action="{{ route('user.deleteProduct', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="px-4 py-2 mt-2 text-white bg-red-500 rounded-lg hover:bg-red-600">Delete Product</button>
                        </form>
                    </div>
                    @empty
                    <p class="text-gray-500">No products posted.</p>
                    @endforelse
                </div>

                <!-- Deactivate Button with JS Pop-up -->
                <div class="flex justify-center mt-4">
                    @if($user->user_type != 'inactive')
            
                    @endif
                    <form action="{{ route('user.delete', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 ml-4 text-white bg-red-500 rounded-lg hover:bg-red-600">Delete User</button>
                    </form>
                </div>
            </div>
            @empty
            <p>No users found.</p>
            @endforelse
        </div>

       

        <!-- Modal for Image Preview -->
        <div class="modal" :class="{ 'active': imageModal }" @click="imageModal = false">
            <img :src="modalImage" alt="Image Preview">
        </div>

    </div>

</body>

</html>
