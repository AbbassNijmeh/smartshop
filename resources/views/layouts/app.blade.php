<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $setting->name }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,700;0,800;1,200;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <!-- Add these in your <head> section -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Optional Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{asset('admin_assets/css/animate.css')}}">

    <link rel="stylesheet" href="{{asset('admin_assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin_assets/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin_assets/css/magnific-popup.css')}}">

    <link rel="stylesheet" href="{{asset('admin_assets/css/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('admin_assets/css/style.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.dataTables.min.css" rel="stylesheet">
    <style>
        /* Error/Success Message Container */
        .message-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            width: 90%;
        }

        /* Base Message Card */
        .message-card {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            animation: slideIn 0.3s ease-out forwards;
            position: relative;
            overflow: hidden;
        }

        /* Error Message Styling */
        .error-card {
            background: #fff0f0;
            border-left: 4px solid #dc3545;
        }

        .error-icon {
            color: #dc3545;
        }

        .error-message {
            color: #721c24;
        }

        /* Success Message Styling */
        .success-card {
            background: #f0fff4;
            border-left: 4px solid #28a745;
        }

        .success-icon {
            color: #28a745;
        }

        .success-message {
            color: #155724;
        }

        /* Warning Message Styling */
        .warning-card {
            background: #fffaf0;
            border-left: 4px solid #ffc107;
        }

        .warning-icon {
            color: #ffc107;
        }

        .warning-message {
            color: #856404;
        }

        /* Common Icon Styling */
        .message-icon {
            font-size: 1.5rem;
            margin-right: 1rem;
            min-width: 24px;
        }

        /* Message Content */
        .message-content {
            flex-grow: 1;
            font-size: 0.9rem;
            padding-right: 1.5rem;
        }

        /* Dismiss Button */
        .message-dismiss {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            color: inherit;
            opacity: 0.7;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .message-dismiss:hover {
            opacity: 1;
        }

        /* Animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                height: 0;
                padding: 0;
                margin: 0;
                transform: translateX(100%);
            }
        }

        /* Stacking effect for multiple messages */
        .message-stack div:nth-child(1) {
            z-index: 3;
        }

        .message-stack div:nth-child(2) {
            transform: translateY(20px) scale(0.95);
            z-index: 2;
        }

        .message-stack div:nth-child(3) {
            transform: translateY(40px) scale(0.9);
            z-index: 1;
        }

        .message-stack div:nth-child(n+4) {
            display: none;
        }

        /* Footer Styling (existing) */
        .ftco-footer {
            border-top: 3px solid #007bff;
        }

        .ftco-footer a:hover {
            color: #007bff !important;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        .social-icons a {
            transition: transform 0.3s ease;
        }

        .social-icons a:hover {
            transform: translateY(-3px);
        }
    </style>
@stack('styles')
<body>
    <!-- Error Messages -->
    @if($errors->any())
    <div class="message-container">
        <div class="message-stack">
            @foreach ($errors->all() as $error)
            <div class="message-card error-card">
                <div class="message-icon error-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="message-content error-message">
                    {{ $error }}
                </div>
                <button class="message-dismiss" onclick="this.parentElement.style.animation='fadeOut 0.3s forwards'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Success Messages -->
    @if(session('success'))
    <div class="message-container">
        <div class="message-stack">
            <div class="message-card success-card">
                <div class="message-icon success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="message-content success-message">
                    {{ session('success') }}
                </div>
                <button class="message-dismiss" onclick="this.parentElement.style.animation='fadeOut 0.3s forwards'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Warning/Error Messages -->
    @if (session('error'))
    <div class="message-container">
        <div class="message-stack">
            <div class="message-card warning-card">
                <div class="message-icon warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="message-content warning-message">
                    {{ session('error') }}
                </div>
                <button class="message-dismiss" onclick="this.parentElement.style.animation='fadeOut 0.3s forwards'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="wrap">
        <div class="container">
            <div class="row align-items-center">


                <!-- Auth Links Column -->
                <div class="col-12 col-md-6 py-2">
                    <div
                        class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-end">

                        <div class="reg">
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                @guest
                                <div class="d-flex ">
                                    @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm mr-2">Sign
                                        Up</a>
                                    @endif
                                    @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="btn btn-success btn-sm">Log In</a>
                                    @endif
                                </div>
                                @else
                                <div class="d-flex flex-column flex-md-row align-items-center">
                                    <span class="mr-md-2 mb-1 mb-md-0 text-white">Welcome, {{ Auth::user()->name
                                        }}</span>
                                    <a href="#" class="btn btn-success  btn-sm" data-toggle="modal"
                                        data-target="#logoutModal">
                                        Logout
                                    </a>
                                </div>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar"
        style="background: ">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">{{ $setting->name }}</a>
            <div class="order-lg-last btn-group">
                <a href="#" class="btn-cart dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="flaticon-shopping-bag"></span>
                    <div class="d-flex justify-content-center align-items-center">
                        <small>
                            @if (Auth::check())
                            {{ Auth::user()->cart->count() }}
                            @else
                            0
                            @endif
                        </small>
                    </div>
                </a>
                @guest
                <!-- Show something for guests (if needed) -->
                @else
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach(Auth::user()->cart->take(4) as $cartItem )
                    <div class="dropdown-item d-flex align-items-start" href="#">
                        <div class="img" style="background-image: url({{ asset( $cartItem->product->image) }});">
                            <!-- Assuming each product has an 'image' attribute that stores the image path -->
                        </div>
                        <div class="text pl-3">
                            <h4>{{ $cartItem->product->name }}</h4>
                            <p class="mb-0">
                                <a href="#" class="price">${{ number_format($cartItem->product->price, 2) }}</a>
                                <span class="quantity ml-3">Quantity: {{ $cartItem->quantity }}</span>
                            </p>
                        </div>
                    </div>
                    @endforeach

                    @if(Auth::user()->cart->isEmpty())
                    <p class="text-center">Your cart is empty.</p>
                    @endif

                    <a class="dropdown-item text-center btn-link d-block w-100" href="{{ route('cart.show') }}">
                        View All
                        <span class="ion-ios-arrow-round-forward"></span>
                    </a>
                </div>
                @endguest

            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>

            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a href="{{ route('home') }}" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="about.html" class="nav-link">About</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Products</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="{{ route('products') }}">Products</a>
                            <a class="dropdown-item" href="{{ route('wishlist.index') }}">Wishlist</a>
                            <a class="dropdown-item" href="{{ route('cart.show') }}">Cart</a>
                            <a class="dropdown-item" href="{{ route('checkout') }}">Checkout</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Profile</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="{{ route('profile.show') }}">Manage Profile</a>
                            <a class="dropdown-item" href="{{ route('user.allergies') }}">Manage allergies</a>
                            <a class="dropdown-item" href="{{ route('user.orderHistory') }}">Order History</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- END nav -->

    @yield('body')

    <footer class="ftco-footer bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Company Info -->
                <div class="col-md-6 col-lg-3">
                    <div class="mb-4">
                        <h2 class="h4 mb-3">
                            <a href="#" class="text-white text-decoration-none">{{ $setting->name }}</a>
                        </h2>
                        <p>Far far away, behind the word mountains, far from the countries.</p>
                        <div class="social-icons mt-4">
                            <a href="{{ $setting->facebook }}" class="text-white me-3" target="_blank">
                                <i class="fab fa-facebook-f fa-lg"></i>
                            </a>
                            <a href="{{ $setting->twitter }}" class="text-white me-3" target="_blank">
                                <i class="fab fa-twitter fa-lg"></i>
                            </a>
                            <a href="{{ $setting->instagram }}" class="text-white me-3" target="_blank">
                                <i class="fab fa-instagram fa-lg"></i>
                            </a>
                            <a href="{{ $setting->tiktok }}" class="text-white" target="_blank">
                                <i class="fab fa-tiktok fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-md-6 col-lg-3">
                    <div class="mb-4 ">
                        <h3 class="h5 mb-3 text-white">My Account</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('profile.show') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-user-circle me-2"></i>My Profile
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('user.orderHistory') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-history me-2"></i>Order History
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('login') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-user-plus me-2"></i>Register
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-md-6 col-lg-3">
                    <div class="mb-4">
                        <h3 class="h5 mb-3  text-white">Contact Us</h3>
                        <ul class="list-unstyled text-muted">
                            <li class="mb-3">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                {{ $setting->address }}
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-phone-volume me-2 text-primary"></i>
                                <a href="tel:{{ $setting->phone }}" class="text-white text-decoration-none">
                                    {{ $setting->phone }}
                                </a>
                            </li>
                            <li>
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                <a href="mailto:{{ $setting->email }}" class="text-white text-decoration-none">
                                    {{ $setting->email }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="col-md-6 col-lg-3">
                    <div class="mb-4">
                        <h3 class="h5 mb-3  text-white">Newsletter</h3>
                        <form class="subscribe-form">
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Enter your email">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                        <small class="text-muted">Subscribe to get latest updates</small>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <p class="mb-0 text-muted">
                        © {{ date('Y') }} {{ $setting->name }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="button"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                stroke="#F96D00" />
        </svg></div>

    <script>
        function showMessage(message, backgroundColor) {
    $('body').append(`
        <div id="success-message" style="position: fixed; top: 20px; left: 20px; padding: 10px 20px;
            background-color: ${backgroundColor}; color: white; border-radius: 5px; z-index: 1000; font-size: 16px;">
            ${message}
        </div>`
    );
    setTimeout(function () {
        $('#success-message').fadeOut('slow', function () {
            $(this).remove();
        });
    }, 4000);
}
    </script>
    <script src="{{asset('admin_assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/jquery-migrate-3.0.1.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/popper.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/jquery.easing.1.3.js')}}"></script>
    <script src="{{asset('admin_assets/js/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/jquery.stellar.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/jquery.animateNumber.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/scrollax.min.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false">
    </script>
    <script src="{{asset('admin_assets/js/google-map.js')}}"></script>
    <script src="{{asset('admin_assets/js/main.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    @stack('script')

</body>

</html>
