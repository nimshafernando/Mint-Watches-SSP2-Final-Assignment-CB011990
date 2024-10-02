<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Fredioka Font -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

        .centered-heading {
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
            color: #2c3e50;
        }

        /* Modal Styling */
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

        .modal.active {
            display: flex;
        }

        .modal img {
            max-width: 80%;
            max-height: 80%;
        }

        /* Custom styles for the image slider */
        .slider-container {
            position: relative;
            max-width: 100%;
            height: 300px;
            margin: auto;
        }

        .slider-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .slider-image.active {
            display: block;
        }

        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2rem;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
        }

        .left-arrow {
            left: 10px;
        }

        .right-arrow {
            right: 10px;
        }
    </style>
</head>

<body class="bg-gray-100" x-data="{ showModal: false, modalImage: '', currentImageIndex: 0 }">
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

    <!-- Content -->
    <div class="p-8 ml-64 space-y-10">
        <!-- Centered Admin Dashboard Heading -->
        <h1 class="centered-heading">Admin Approval Board</h1>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
            <div class="flex items-center p-6 text-white bg-blue-500 rounded-lg shadow-lg custom-card">
                <i class="mr-4 fas fa-thumbs-up fa-2x"></i>
                <div>
                    <h3 class="text-lg font-semibold">Total Approved</h3>
                    <p class="text-2xl">{{ $approvedProducts->count() }}</p>
                </div>
            </div>
            <div class="flex items-center p-6 text-white bg-yellow-500 rounded-lg shadow-lg custom-card">
                <i class="mr-4 fas fa-hourglass-half fa-2x"></i>
                <div>
                    <h3 class="text-lg font-semibold">Total Pending</h3>
                    <p class="text-2xl">{{ $pendingProducts->count() }}</p>
                </div>
            </div>
            <div class="flex items-center p-6 text-white bg-red-500 rounded-lg shadow-lg custom-card">
                <i class="mr-4 fas fa-times fa-2x"></i>
                <div>
                    <h3 class="text-lg font-semibold">Total Declined</h3>
                    <p class="text-2xl">{{ $declinedProducts->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Products Section -->
        <h3 class="mb-6 text-2xl font-semibold text-gray-800">Pending Products</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($pendingProducts as $product)
            <div class="relative p-6 space-y-4 transition-all bg-yellow-100 shadow-lg rounded-2xl hover:shadow-xl custom-card">
                
                <!-- Product Name Centered -->
                <h4 class="text-lg font-bold text-center text-gray-800">{{ $product->name }}</h4>

                <!-- Image Slider (with left and right arrows) -->
                <div class="slider-container" x-data="{ images: {{ json_encode(json_decode($product->images)) }}, currentIndex: 0 }">
                    <template x-for="(image, index) in images" :key="index">
                        <img :src="`{{ asset('storage/') }}/` + image" alt="Product Image" class="slider-image" :class="{ 'active': currentIndex === index }">
                    </template>

                    <!-- Left Arrow -->
                    <div class="arrow left-arrow" @click="currentIndex = currentIndex > 0 ? currentIndex - 1 : images.length - 1">
                        <i class="fas fa-chevron-left"></i>
                    </div>

                    <!-- Right Arrow -->
                    <div class="arrow right-arrow" @click="currentIndex = currentIndex < images.length - 1 ? currentIndex + 1 : 0">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="space-y-1">
                    <p class="text-sm text-gray-500"><strong>Brand:</strong> {{ $product->brand }}</p>
                    <p class="text-sm text-gray-500"><strong>Category:</strong> {{ $product->category }}</p>
                    <p class="text-sm text-gray-500"><strong>Price:</strong> ${{ $product->price }}</p>
                    <p class="text-sm text-gray-500"><strong>Description:</strong> {{ $product->description }}</p>
                    <p class="text-sm text-gray-500"><strong>Posted by:</strong> {{ $product->user->name }}</p>
                </div>

                <!-- Approve and Decline Buttons -->
                <div class="flex justify-between mt-4">
                    <form action="{{ route('products.approve', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="px-4 py-2 text-white transition-all bg-green-500 rounded-lg shadow hover:bg-green-400">
                            Approve
                        </button>
                    </form>
                    <form action="{{ route('products.decline', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="px-4 py-2 text-white transition-all bg-red-500 rounded-lg shadow hover:bg-red-400">
                            Decline
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-1 text-center text-gray-500 md:col-span-2 lg:col-span-3">
                <p>No pending products to show.</p>
            </div>
            @endforelse
        </div>

        <!-- Approved Products Section -->
        <h3 class="mb-6 text-2xl font-semibold text-gray-800">Approved Products</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($approvedProducts as $product)
            <div class="relative p-6 space-y-4 transition-all bg-green-100 shadow-lg rounded-2xl hover:shadow-xl custom-card">
                
                <!-- Product Name Centered -->
                <h4 class="text-lg font-bold text-center text-gray-800">{{ $product->name }}</h4>

                <!-- Display All Images (clickable for modal pop-up) -->
                <div class="grid grid-cols-2 gap-2">
                    @foreach(json_decode($product->images) as $image)
                    <img src="{{ asset('storage/' . $image) }}" alt="Product Image"
                        class="object-cover w-full h-32 transition rounded cursor-pointer hover:opacity-75"
                        @click="modalImage = '{{ asset('storage/' . $image) }}'; showModal = true;">
                    @endforeach
                </div>

                <!-- Product Details -->
                <div class="space-y-1">
                    <p class="text-sm text-gray-500"><strong>Brand:</strong> {{ $product->brand }}</p>
                    <p class="text-sm text-gray-500"><strong>Category:</strong> {{ $product->category }}</p>
                    <p class="text-sm text-gray-500"><strong>Price:</strong> ${{ $product->price }}</p>
                    <p class="text-sm text-gray-500"><strong>Description:</strong> {{ $product->description }}</p>
                    <p class="text-sm text-gray-500"><strong>Posted by:</strong> {{ optional($product->user)->name ?? 'Unknown' }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-1 text-center text-gray-500 md:col-span-2 lg:col-span-3">
                <p>No approved products to show.</p>
            </div>
            @endforelse
        </div>

        <!-- Declined Products Section -->
        <h3 class="mb-6 text-2xl font-semibold text-gray-800">Declined Products</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($declinedProducts as $product)
            <div class="relative p-6 space-y-4 transition-all bg-red-100 shadow-lg rounded-2xl hover:shadow-xl custom-card">
                
                <!-- Product Name Centered -->
                <h4 class="text-lg font-bold text-center text-gray-800">{{ $product->name }}</h4>

                <!-- Display All Images (clickable for modal pop-up) -->
                <div class="grid grid-cols-2 gap-2">
                    @foreach(json_decode($product->images) as $image)
                    <img src="{{ asset('storage/' . $image) }}" alt="Product Image"
                        class="object-cover w-full h-32 transition rounded cursor-pointer hover:opacity-75"
                        @click="modalImage = '{{ asset('storage/' . $image) }}'; showModal = true;">
                    @endforeach
                </div>

                <!-- Product Details -->
                <div class="space-y-1">
                    <p class="text-sm text-gray-500"><strong>Brand:</strong> {{ $product->brand }}</p>
                    <p class="text-sm text-gray-500"><strong>Category:</strong> {{ $product->category }}</p>
                    <p class="text-sm text-gray-500"><strong>Price:</strong> ${{ $product->price }}</p>
                    <p class="text-sm text-gray-500"><strong>Description:</strong> {{ $product->description }}</p>
                    <p class="text-sm text-gray-500"><strong>Posted by:</strong> {{ $product->user->name }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-1 text-center text-gray-500 md:col-span-2 lg:col-span-3">
                <p>No declined products to show.</p>
            </div>
            @endforelse
        </div>

        <!-- Image Modal -->
        <div x-show="showModal" class="modal" @click.away="showModal = false">
            <img :src="modalImage" alt="Product Image" class="object-cover rounded-lg">
        </div>
    </div>

</body>

</html>
