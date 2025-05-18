<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #f72585;
        }

        body {
            background-color: #f8f9fa;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white !important;
        }

        .order-card {
            border: none;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-left: 4px solid transparent;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .order-card.active {
            border-left: 4px solid var(--primary-color);
            background-color: #f8f9ff;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-shipped {
            background-color: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .map-container {
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #delivery-map {
            height: 400px;
            border-radius: 10px;
        }

        .delivery-info {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-container i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #6c757d;
        }

        .search-input {
            padding-left: 40px;
            border-radius: 20px;
            border: 1px solid #e0e0e0;
        }

        .total-price-card {
            background: #f8f9ff;
            border-left: 3px solid var(--primary-color);
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .items-toggle-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            padding: 0;
            font-weight: 500;
            transition: all 0.3s;
        }

        .items-toggle-btn:hover {
            opacity: 0.8;
        }

        .order-items-collapse {
            transition: all 0.3s ease;
            overflow: hidden;
            max-height: 500px;
        }

        .order-items-collapse.collapsed {
            max-height: 0 !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light bg-light px-4">
        <span class="navbar-brand mb-0 h1">Delivery Dashboard</span>
        <div class="d-flex align-items-center">
            <span class="me-3">{{ Auth::user()->name }}</span>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                Logout
            </button>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Order List -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Upcoming Orders</h4>
                    <div class="search-container">
                        <input type="text" id="orderSearch" class="form-control search-input"
                            placeholder="Search by name, ID, or phone">
                    </div>
                </div>

                @if ($orders->isEmpty())
                <div class="alert alert-info">
                    No upcoming orders at the moment.
                </div>
                @else
                @foreach ($orders as $order)
                <div class="order-card" data-order-id="{{ $order->id }}" data-name="{{ $order->user->name }}"
                    data-phone="{{ $order->user->phone }}" data-lat="{{ $order->userAddress->latitude }}"
                    data-lng="{{ $order->userAddress->longtitude }}" data-address="@if($order->userAddress->location_link)
                      {{ $order->userAddress->location_link }}
                   @elseif($order->userAddress->latitude && $order->userAddress->longtitude)
                      Coordinates: {{ $order->userAddress->latitude }}, {{ $order->userAddress->longtitude }}
                   @else
                      {{ $order->userAddress->street }},
                      {{ $order->userAddress->building }},
                      {{ $order->userAddress->city }},
                      {{ $order->userAddress->country }}
                   @endif">

                    <p class="h5">{{ $order->user->name }}, Order #{{ $order->id }}</p>
                    <p class="h5">Phone number: {{ $order->user->phone }}</p>
                    <span class="badge bg-success status-badge">{{ ucfirst($order->status) }}</span>

                    <p>
                        @if ($order->userAddress->location_link)
                        <a href="{{ $order->userAddress->location_link }}" target="_blank">
                            {{ $order->userAddress->location_link }}
                        </a>
                        @elseif ($order->userAddress->latitude && $order->userAddress->longtitude)
                        Coordinates: {{ $order->userAddress->latitude }}, {{ $order->userAddress->longtitude }}
                        @else
                        {{ $order->userAddress->street }}, {{ $order->userAddress->building }},
                        {{ $order->userAddress->city }}, {{ $order->userAddress->country }}
                        @endif
                    </p>

                    {{-- Total Price --}}
                    <div class="total-price-card">
                        <strong>Total Price:</strong>
                        ${{ number_format($order->orderItems->sum(fn($item) => ($item->product->price ?? 0) *
                        $item->quantity), 2) }}
                    </div>

                    {{-- Toggle Items --}}
                    <button class="items-toggle-btn mt-2" onclick="toggleItems(this)">
                        Show Items
                    </button>

                    {{-- Items List --}}
                    <ul class="order-items-collapse collapsed mt-2">
                        @foreach ($order->orderItems as $item)
                        <li>{{ $item->quantity }} x {{ $item->product->name ?? 'Unknown Product' }}
                        </li> @endforeach
                    </ul>

                    <a href="" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#otpModal">
                        Mark as Delivered
                    </a>

                </div>
                <!-- OTP Modal -->
                <div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method="POST" action="{{ route('delivery.completed') }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="otpModalLabel">Enter OTP to Confirm Delivery</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-bs-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="otp">OTP</label>
                                        <input type="text" name="otp" class="form-control" id="otp" required>
                                    </div>
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Confirm Delivery</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
                @endif
            </div>

            <!-- Map Panel -->
            <div class="col-lg-4">
                <h4>Delivery Route</h4>
                <div id="delivery-map" style="height: 400px; border-radius: 10px;"></div>
                <div class="delivery-info">
                    <h6>Route Information</h6>
                    <div id="route-instructions"></div>
                    <div id="route-summary" class="mt-2"></div>
                </div>
            </div>
        </div>
    </div>


    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Yes, Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fixed starting point coordinates (Rawche, Beirut)
        const STARTING_POINT = [33.8964, 35.4823];
        let map, routingControl;

        // Initialize the map
        function initMap() {
            map = L.map('delivery-map').setView(STARTING_POINT, 13);

            // Add OSM tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add starting point marker
            L.marker(STARTING_POINT)
                .addTo(map)
                .bindPopup('<div class="starting-point-marker">Starting Point<br>Rawche, Beirut</div>')
                .openPopup();

            addOrderMarkers();
        }

        // Add order markers and setup interactions
        function addOrderMarkers() {
            const orderCards = document.querySelectorAll('.order-card');
            let waypoints = [L.latLng(STARTING_POINT)];

            orderCards.forEach(card => {
                const lat = parseFloat(card.dataset.lat);
                const lng = parseFloat(card.dataset.lng);

                if (!isNaN(lat) && !isNaN(lng)) {
                    const marker = L.marker([lat, lng]).addTo(map)
                        .bindPopup(`<b>Order #${card.dataset.orderId}</b><br>${card.dataset.address}`);

                    marker.orderCard = card;
                    waypoints.push(L.latLng(lat, lng));

                    // Add click events
                    marker.on('click', () => highlightOrderCard(card));
                    card.addEventListener('click', () => {
                        highlightOrderCard(card);
                        map.panTo([lat, lng]);
                        marker.openPopup();
                    });
                }
            });

            if (waypoints.length > 1) {
                calculateRoute(waypoints);
            }
        }

        // Calculate delivery route
        function calculateRoute(waypoints) {
            if (routingControl) {
                map.removeControl(routingControl);
            }

            routingControl = L.Routing.control({
                waypoints: waypoints,
                routeWhileDragging: true,
                showAlternatives: false,
                addWaypoints: false,
                draggableWaypoints: false,
                fitSelectedRoutes: true,
                lineOptions: {
                    styles: [{color: '#007bff', opacity: 0.7, weight: 5}]
                },
                createMarker: function() { return null; }
            }).addTo(map);

            routingControl.on('routesfound', e => {
                const routes = e.routes;
                const summary = routes[0].summary;

                document.getElementById('route-summary').innerHTML = `
                    <p><strong>Total distance:</strong> ${(summary.totalDistance / 1000).toFixed(1)} km</p>
                    <p><strong>Estimated time:</strong> ${Math.round(summary.totalTime / 60)} minutes</p>
                `;

                let instructionsHtml = '<ol class="list-unstyled">';
                routes[0].instructions.forEach(instruction => {
                    instructionsHtml += `<li>${instruction.text}</li>`;
                });
                instructionsHtml += '</ol>';
                document.getElementById('route-instructions').innerHTML = instructionsHtml;
            });

            // Adjust map view to show all points
            const bounds = L.latLngBounds(waypoints);
            map.fitBounds(bounds, {padding: [50, 50]});
        }

        // Highlight active order card
        function highlightOrderCard(card) {
            document.querySelectorAll('.order-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
        }

        // Initialize the map when page loads
        document.addEventListener('DOMContentLoaded', initMap);
        // Toggle order item visibility
function toggleItems(button) {
    const collapse = button.nextElementSibling;
    const isCollapsed = collapse.classList.contains('collapsed');
    collapse.classList.toggle('collapsed');
    button.textContent = isCollapsed ? 'Hide Items' : 'Show Items';
}

// Search filtering
document.getElementById('orderSearch').addEventListener('input', function () {
    const term = this.value.toLowerCase();
    document.querySelectorAll('.order-card').forEach(card => {
        const id = card.dataset.orderId.toString();
        const name = card.dataset.name.toLowerCase();
        const phone = card.dataset.phone.toLowerCase();
        const visible = id.includes(term) || name.includes(term) || phone.includes(term);
        card.style.display = visible ? '' : 'none';
    });
});

    </script>
</body>

</html>
