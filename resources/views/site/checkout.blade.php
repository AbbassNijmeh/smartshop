@extends('layouts.app')
@section('body')
<section class="hero-wrap hero-wrap-2" style="background-image: url({{asset('assets/img/bg-1.jpg')}});"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate mb-5 text-center">
                <p class="breadcrumbs mb-0"><span class="mr-2"><a href="{{ route('home') }}">Home <i
                                class="fa fa-chevron-right"></i></a></span> <span>Checkout <i
                            class="fa fa-chevron-right"></i></span></p>
                <h2 class="mb-0 bread">Checkout</h2>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 ftco-animate">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    <h3 class="mb-4 billing-heading">Billing Details</h3>
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fullname">Full Name</label>
                                <input type="text" class="form-control" name="full_name"
                                    value="{{ Auth::user()->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ Auth::user()->phone ?? '' }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Option -->
                    <div class="form-group">
                        <label>Do you want your order delivered?</label><br>
                        <label><input type="radio" name="delivery_option" value="yes" checked
                                onchange="toggleDeliveryFields()"> Yes</label>
                        <label class="ml-3"><input type="radio" name="delivery_option" value="no"
                                onchange="toggleDeliveryFields()"> No (I'll pick it up)</label>
                    </div>

                    <!-- Address Section -->
                    <div id="delivery_location_section">
                        @if($addresses->isNotEmpty())
                        <div class="form-group" id="previousAddress">
                            <label>Select Previous Address:</label>
                            <select class="form-control" name="address_id" id="address_id"
                                onchange="toggleNewAddressSection()">
                                <option value="">-- Add New Address --</option>
                                @foreach ($addresses as $address)
                                <option value="{{ $address->id }}">
                                    @if ($address->location_link)
                                    {{ $address->location_link }}
                                    @elseif ($address->latitude || $address->longtitude)
                                    Coordinates: {{ $address->longtitude }} - {{ $address->latitude }}
                                    @else
                                    {{ $address->street }}, {{ $address->city }} - {{ $address->country }}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div id="new_address_form" @if($addresses->isNotEmpty()) style="display:none;" @endif>
                            <h3 class="mb-4">Delivery Address</h3>
                            <div class="form-group">
                                <label>Location Type</label>
                                <select class="form-control" name="location_type" id="location_type"
                                    onchange="toggleLocationFields()">
                                    <option value="">-- Select Type --</option>
                                    <option value="custom">Custom Address</option>
                                    <option value="link">Location Link</option>
                                    <option value="current">Use Current Location</option>
                                </select>
                            </div>

                            <!-- Custom Address -->
                            <div id="custom_address_fields" style="display:none;">
                                <div class="form-group">
                                    <input type="text" name="country" class="form-control" placeholder="Country">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" placeholder="City">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="street" class="form-control" placeholder="Street">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="building" class="form-control" placeholder="Building/Apartment">
                                </div>
                            </div>

                            <!-- Location Link -->
                            <div id="location_link_field" style="display:none;">
                                <div class="form-group">
                                    <input type="url" name="location_link" class="form-control" placeholder="Google Maps Link">
                                </div>
                            </div>

                            <!-- Current Location -->
                            <div id="current_location_info" style="display:none;">
                                <p id="current_location_display">Fetching location...</p>
                                <input type="hidden" name="location_coordinates" id="location_coordinates">
                                <div id="map" style="height:300px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <h3 class="billing-heading mb-4">Payment Method</h3>
                    <div class="form-group">
                        <label><input type="radio" name="payment_method" value="credit_card" checked
                                onchange="togglePaymentMethod()"> Visa Card</label><br>
                        <label><input type="radio" name="payment_method" value="paypal"
                                onchange="togglePaymentMethod()"> PayPal</label>
                    </div>

                    <div id="visa-form">
                        <input type="text" name="card_number" class="form-control mb-2" placeholder="Card Number">
                        <input type="text" name="card_name" class="form-control mb-2" placeholder="Cardholder Name">
                        <input type="text" name="expiry_date" class="form-control mb-2" placeholder="MM/YY">
                        <input type="text" name="cvv" class="form-control mb-2" placeholder="CVV">
                    </div>

                    <div id="paypal-form" style="display:none;">
                        <input type="email" name="paypal_email" class="form-control" placeholder="PayPal Email">
                    </div>

                    <input type="hidden" name="selected_payment_method" id="selected_payment_method"
                        value="credit_card">

                    <div class="form-group mt-4">
                        <button type="button" class="btn btn-primary py-3 px-4" onclick="submitCheckout()">Confirm
                            Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    function togglePaymentMethod() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        document.getElementById('visa-form').style.display = method === 'credit_card' ? 'block' : 'none';
        document.getElementById('paypal-form').style.display = method === 'paypal' ? 'block' : 'none';
        document.getElementById('selected_payment_method').value = method;
    }

    function toggleLocationFields() {
        const type = document.getElementById('location_type').value;
        document.getElementById('custom_address_fields').style.display = type === 'custom' ? 'block' : 'none';
        document.getElementById('location_link_field').style.display = type === 'link' ? 'block' : 'none';
        document.getElementById('current_location_info').style.display = type === 'current' ? 'block' : 'none';
        if (type === 'current') getCurrentLocation();
    }

    function toggleDeliveryFields() {
        const deliveryOption = document.querySelector('input[name="delivery_option"]:checked').value;
        document.getElementById('delivery_location_section').style.display = deliveryOption === 'yes' ? 'block' : 'none';
        const prevAddress = document.getElementById('previousAddress');
        if (prevAddress) prevAddress.style.display = deliveryOption === 'yes' ? 'block' : 'none';
    }

    function toggleNewAddressSection() {
        const addressSelect = document.getElementById('address_id');
        const newAddressSection = document.getElementById('new_address_form');

        // If there's no address select (empty addresses), always show the form
        if (!addressSelect) {
            newAddressSection.style.display = 'block';
            return;
        }

        const selectedAddress = addressSelect.value;
        newAddressSection.style.display = selectedAddress === '' ? 'block' : 'none';
    }

    function getCurrentLocation() {
        const display = document.getElementById('current_location_display');
        const input = document.getElementById('location_coordinates');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                input.value = `${lat},${lng}`;
                display.innerHTML = `Latitude: ${lat}<br>Longitude: ${lng}`;
                const map = L.map('map').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                L.marker([lat, lng]).addTo(map).bindPopup('You are here').openPopup();
            }, () => {
                display.innerText = 'Unable to fetch location.';
            });
        } else {
            display.innerText = 'Geolocation is not supported.';
        }
    }

    function submitCheckout() {
        document.getElementById('checkout-form').submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleDeliveryFields();
        toggleLocationFields();
        toggleNewAddressSection();
    });
</script>
@endsection
