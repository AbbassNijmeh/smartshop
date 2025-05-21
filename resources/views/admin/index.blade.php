@extends('layouts.admin')
@section('body')


<!-- Main Content -->
<div id="content">

    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>



        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
                <!-- Dropdown - Messages -->
                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                    aria-labelledby="searchDropdown">
                    <form class="form-inline mr-auto w-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>


            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small p-1">
                        {{ Auth::user()->name }} - {{ Auth::user()->role }}
                    </span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item text-center"> {{ Auth::user()->name }}</a>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>

        </ul>

    </nav>
    <!-- End of Topbar -->

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Earnings (Monthly)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">$ {{ $monthlyProfit }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Earnings (Annual)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">$ {{ $totalProfit }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Orders
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalOrders }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pending Orders
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-danger">{{ $pendingOrders }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->


        <div class="row">
            <!-- Area Chart -->
            <div class="col-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->

                    <div class="chart-controls mx-4 ">
                        <select id="timePeriod">
                            <option value="30">Last 30 Days</option>
                            <option value="60">Last 60 Days</option>
                            <option value="90">Last 90 Days</option>
                        </select>
                        <button onclick="updateChart()">Update</button>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Products Chart -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Sales by Category</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4">
                            <canvas id="categorySalesChart"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>
@endsection
@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Category Sales Chart
    fetch('/category-sales')
        .then(response => response.json())
        .then(data => {
            console.log(data);

            const ctx = document.getElementById('categorySalesChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: data.colors,
                        hoverBackgroundColor: data.colors.map(color => color + 'dd'),
                        borderWidth: 0
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    return `${label}: $${value.toFixed(2)}`;
                                }
                            }
                        }
                    }
                },
               plugins: [{
    id: 'datalabels',
    afterDatasetsDraw: (chart) => {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
            const meta = chart.getDatasetMeta(i);
            const total = dataset.data.reduce((a, b) => a + parseFloat(b), 0);

            meta.data.forEach((element, index) => {
                const value = parseFloat(dataset.data[index]);
                const percentage = total > 0
                    ? Math.round((value / total) * 100)
                    : 0;

                ctx.fillStyle = 'white';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = '12px Arial';

                const position = element.tooltipPosition();
                ctx.fillText(`${percentage}%`, position.x, position.y);
            });
        });
    }
}]
            });
        });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Top Products Chart
    fetch('/top-products')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('topProductsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Units Sold',
                        data: data.data,
                        backgroundColor: '#4e73df',
                        borderColor: '#4e73df',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Makes horizontal bar chart
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Units Sold'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                autoSkip: false
                            }
                        }
                    }
                }
            });
        });
});
</script>


<<script>
    let monthlyChart = null;

    function updateChart() {
    const period = document.getElementById('timePeriod').value;
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - parseInt(period));

    fetch(`/orders/monthly-data?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}`)
    .then(response => response.json())
    .then(data => {
    const ctx = document.getElementById('monthlyChart').getContext('2d');

    if (monthlyChart) monthlyChart.destroy();

    monthlyChart = new Chart(ctx, {
    type: 'line',
    data: {
    labels: data.months,
    datasets: [{
    label: 'Total Sales',
    data: data.sales,
    borderColor: '#3e95cd',
    backgroundColor: 'rgba(62, 149, 205, 0.2)',
    tension: 0.4,
    pointRadius: 3,
    pointHoverRadius: 5,
    pointBorderWidth: 2,
    pointBackgroundColor: '#fff'
    },
    {
    label: 'Total Profit',
    data: data.profits,
    borderColor: '#4caf50',
    backgroundColor: 'rgba(76, 175, 80, 0.2)',
    tension: 0.4,
    pointRadius: 3,
    pointHoverRadius: 5,
    pointBorderWidth: 2,
    pointBackgroundColor: '#fff'
    }]
    },
    options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
    title: {
    display: true,
    text: 'Sales vs. Profit Analysis'
    },
    legend: {
    position: 'top'
    }
    },
    scales: {
    x: {
    grid: {
    display: false
    },
    ticks: {
    maxRotation: 0,
    autoSkipPadding: 20
    }
    },
    y: {
    beginAtZero: true,
    title: {
    display: true,
    text: 'Amount ($)'
    },
    grid: {
    color: '#f5f5f5'
    }
    }
    },
    elements: {
    line: {
    borderWidth: 2
    }
    }
    }
    });
    });
    }

    // Initial load and window resize handler
    document.addEventListener('DOMContentLoaded', updateChart);
    window.addEventListener('resize', () => {
    if (monthlyChart) {
    monthlyChart.destroy();
    updateChart();
    }
    });
    </script>


    @endpush
    @push('styles')

    <style>
        .chart-controls {
            margin: 20px 0;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .chart-container {
            width: 100%;
            min-height: 300px;
            margin: 20px 0;
        }

        select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }

        button {
            padding: 8px 16px;
            background-color: #3e95cd;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #357ebd;
        }

        @media (max-width: 480px) {
            .chart-controls {
                flex-direction: column;
                align-items: stretch;
            }

            select,
            button {
                width: 100%;
            }
        }
    </style>
    @endpush
