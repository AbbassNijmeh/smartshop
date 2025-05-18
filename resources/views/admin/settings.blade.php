@extends('layouts.admin')

@section('body')
<nav class="breadcrumb-container" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Settings</li>
    </ol>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Site Settings</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <!-- Contact Information -->
                        <div class="mb-4">
                            <h5 class="mb-3">Contact Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Business Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{ old('name', $setting->name) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="{{ old('email', $setting->email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                               value="{{ old('phone', $setting->phone) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Physical Address</label>
                                        <textarea class="form-control" id="address" name="address"
                                                  required>{{ old('address', $setting->address) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="mb-4">
                            <h5 class="mb-3">Social Media</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="facebook" class="form-label">Facebook URL</label>
                                        <input type="url" class="form-control" id="facebook" name="facebook"
                                               value="{{ old('facebook', $setting->facebook) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="instagram" class="form-label">Instagram URL</label>
                                        <input type="url" class="form-control" id="instagram" name="instagram"
                                               value="{{ old('instagram', $setting->instagram) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tiktok" class="form-label">TikTok URL</label>
                                        <input type="url" class="form-control" id="tiktok" name="tiktok"
                                               value="{{ old('tiktok', $setting->tiktok) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
