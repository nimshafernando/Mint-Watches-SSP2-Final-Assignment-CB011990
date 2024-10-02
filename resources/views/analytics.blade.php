<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Analytics Dashboard</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fira Sans Font -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        .small-chart {
            height: 250px !important;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="p-6 text-center">Admin Dashboard</h2>
    
        <!-- Moderation Link -->
        <a href="{{ route('dashboard') }}" class="block px-6 py-3 hover:bg-gray-700">Moderation</a>
    
        <!-- Analytics Link -->
        <a href="{{ route('analytics') }}" class="block px-6 py-3 hover:bg-gray-700">Analytics</a>
    
        <!-- User Management Link -->
        <a href="{{ route('userManagement') }}" class="block px-6 py-3 hover:bg-gray-700">User Management</a>
    
        <!-- Category Management Link -->
        <a href="{{ route('categoryManagement') }}" class="block px-6 py-3 hover:bg-gray-700">Category Management</a>
    
        <!-- Revenue Management Link -->
        <a href="{{ route('revenue.management') }}" class="block px-6 py-3 hover:bg-gray-700">Revenue Management</a>
    
        <!-- Log Out Button -->
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
        <h3 class="mb-6 text-3xl font-semibold text-gray-800">Admin Analytics Dashboard</h3>

        <!-- Section Title: Product Analytics -->
        <div class="section-title">Product Analytics</div>
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="p-4 bg-white rounded-lg shadow-md custom-card">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">Product Status Breakdown</h4>
                <canvas id="statusPieChart"></canvas>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-md custom-card">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">Product Listings by Category</h4>
                <canvas id="categoryBarChart"></canvas>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-md custom-card">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">Most Listed Brands</h4>
                <canvas id="brandBarChart"></canvas>
            </div>
        </div>

        <!-- Section Title: Growth and Prices -->
        <div class="section-title">Growth and Price Insights</div>
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
            <div class="p-4 bg-white rounded-lg shadow-md custom-card">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">Monthly Listings Growth</h4>
                <canvas id="growthLineChart"></canvas>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-md custom-card">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">Average Product Price by Category</h4>
                <canvas id="priceLineChart"></canvas>
            </div>
        </div>

        <!-- Section Title: User Analytics -->
        <div class="section-title">User Analytics</div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="p-4 bg-white rounded-lg shadow-md custom-card">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">User Registrations by Month (Doughnut Chart)</h4>
                <canvas id="userDoughnutChart"></canvas>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-md custom-card">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">User Location Distribution (Pie Chart)</h4>
                <canvas id="locationPieChart"></canvas>
            </div>
        </div>

        <!-- Section Title: Popularity and Sales Insights -->
        <div class="section-title">Popularity and Sales Insights</div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="p-4 bg-white rounded-lg shadow-md custom-card small-chart">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">Product Category Popularity (Radar Chart)</h4>
                <canvas id="categoryRadarChart"></canvas>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-md custom-card small-chart">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">Expected Total Revenue (Bar Chart)</h4>
                <canvas id="salesBarChart"></canvas>
            </div>
        </div>

        <!-- New Section: Customer Revenue and Orders Insights -->
        <div class="section-title">Customer Revenue and Orders Insights</div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="p-4 bg-white rounded-lg shadow-md custom-card">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">Customer Total Revenue Earned (Bar Chart)</h4>
                <canvas id="revenueBarChart"></canvas>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-md custom-card">
                <h4 class="mb-4 text-xl font-semibold text-gray-700">Number of Product Orders (Paid vs Unpaid)</h4>
                <canvas id="ordersBarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script>
        const pendingProducts = {{ $pendingProducts }};
        const approvedProducts = {{ $approvedProducts }};
        const declinedProducts = {{ $declinedProducts }};
        const categoryDistribution = {!! json_encode($categoryDistribution) !!};
        const mostListedBrands = {!! json_encode($mostListedBrands) !!};
        const monthlyListingsGrowth = {!! json_encode($monthlyListingsGrowth) !!};
        const avgPriceByCategory = {!! json_encode($avgPriceByCategory) !!};
        const locationDistribution = {!! json_encode($locationDistribution) !!};
        const totalSalesByMonth = {!! json_encode($totalSalesByMonth) !!};
        const topCategories = {!! json_encode($topCategories) !!};
        const customerRevenue = {!! json_encode($customerRevenue) !!}; // Total revenue earned by customers
        const productOrdersByMonth = {!! json_encode($productOrdersByMonth) !!}; // Orders by month
        const paidOrders = {!! json_encode($paidOrders) !!}; // Paid orders
        const unpaidOrders = {!! json_encode($unpaidOrders) !!}; // Unpaid orders

        // Product Status Breakdown (Pie Chart)
        new Chart(document.getElementById('statusPieChart'), {
            type: 'pie',
            data: {
                labels: ['Pending', 'Approved', 'Declined'],
                datasets: [{
                    data: [pendingProducts, approvedProducts, declinedProducts],
                    backgroundColor: ['#FFCE56', '#36A2EB', '#FF6384'],
                }],
            },
        });

        // Product Listings by Category (Bar Chart)
        new Chart(document.getElementById('categoryBarChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(categoryDistribution),
                datasets: [{
                    label: 'Listings',
                    data: Object.values(categoryDistribution),
                    backgroundColor: '#36A2EB',
                    borderColor: '#36A2EB',
                    borderWidth: 1,
                }],
            },
        });

        // Most Listed Brands (Horizontal Bar Chart)
        new Chart(document.getElementById('brandBarChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(mostListedBrands),
                datasets: [{
                    label: 'Listings',
                    data: Object.values(mostListedBrands),
                    backgroundColor: '#FF6384',
                    borderColor: '#FF6384',
                    borderWidth: 1,
                }],
            },
            options: {
                indexAxis: 'y',
            },
        });

        // Monthly Listings Growth (Line Chart)
        new Chart(document.getElementById('growthLineChart'), {
            type: 'line',
            data: {
                labels: Object.keys(monthlyListingsGrowth),
                datasets: [{
                    label: 'Listings Growth',
                    data: Object.values(monthlyListingsGrowth),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                }],
            },
            options: {
                scales: {
                    y: { beginAtZero: true },
                },
            },
        });

        // Average Product Price by Category (Line Chart)
        new Chart(document.getElementById('priceLineChart'), {
            type: 'line',
            data: {
                labels: Object.keys(avgPriceByCategory),
                datasets: [{
                    label: 'Average Price',
                    data: Object.values(avgPriceByCategory),
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 2,
                }],
            },
            options: {
                scales: {
                    y: { beginAtZero: true },
                },
            },
        });

        // User Registrations by Month (Doughnut Chart)
        new Chart(document.getElementById('userDoughnutChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(monthlyListingsGrowth), // Use months for user registration data
                datasets: [{
                    data: Object.values(monthlyListingsGrowth),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                }],
            },
        });

        // User Location Distribution (Pie Chart)
        new Chart(document.getElementById('locationPieChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(locationDistribution),
                datasets: [{
                    data: Object.values(locationDistribution),
                    backgroundColor: ['#FFCE56', '#36A2EB', '#FF6384', '#4BC0C0'],
                }],
            },
        });

        // Product Category Popularity (Radar Chart)
        new Chart(document.getElementById('categoryRadarChart'), {
            type: 'radar',
            data: {
                labels: Object.keys(categoryDistribution),
                datasets: [{
                    label: 'Popularity',
                    data: Object.values(categoryDistribution),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                }],
            },
        });

        // Total Sales by Month (Bar Chart)
        new Chart(document.getElementById('salesBarChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(totalSalesByMonth),
                datasets: [{
                    label: 'Expected Total Sales Revenue',
                    data: Object.values(totalSalesByMonth),
                    backgroundColor: '#4BC0C0',
                    borderColor: '#4BC0C0',
                    borderWidth: 1,
                }],
            },
            options: {
                scales: {
                    y: { beginAtZero: true },
                },
            },
        });

        // Customer Total Revenue Earned (Bar Chart)
        new Chart(document.getElementById('revenueBarChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(customerRevenue),
                datasets: [{
                    label: 'Total Revenue Earned',
                    data: Object.values(customerRevenue),
                    backgroundColor: '#FF6384',
                    borderColor: '#FF6384',
                    borderWidth: 1,
                }],
            },
            options: {
                scales: {
                    y: { beginAtZero: true },
                },
            },
        });

        // Number of Product Orders (Paid vs Unpaid)
        new Chart(document.getElementById('ordersBarChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(productOrdersByMonth),
                datasets: [
                    {
                        label: 'Paid Orders',
                        data: Object.values(paidOrders),
                        backgroundColor: '#36A2EB',
                        borderColor: '#36A2EB',
                        borderWidth: 1,
                    },
                    {
                        label: 'Unpaid Orders',
                        data: Object.values(unpaidOrders),
                        backgroundColor: '#FFCE56',
                        borderColor: '#FFCE56',
                        borderWidth: 1,
                    }
                ],
            },
            options: {
                scales: {
                    y: { beginAtZero: true },
                },
            },
        });
    </script>

</body>

</html>
