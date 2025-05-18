@extends('layouts.app')

@section('body')
<section class="hero-wrap hero-wrap-2" style="background-image: url({{ asset('assets/img/bg-1.jpg') }});" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate mb-5 text-center">
                <p class="breadcrumbs mb-0">
                    <span class="mr-2"><a href="{{ route('home') }}">Home <i class="fa fa-chevron-right"></i></a></span>
                    <span>Allergies <i class="fa fa-chevron-right"></i></span>
                </p>
                <h2 class="mb-0 bread">Manage My Allergies</h2>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body p-4">
                    <h4 class="mb-4">Select Your Allergies</h4>
                    <form action="{{ route('user.allergies.update') }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <label for="allergies">Allergies:</label>
                            <select name="allergies[]" id="allergies" class="form-control select2" multiple>
                                @foreach($allergies as $allergy)
                                    <option value="{{ $allergy->id }}" {{ in_array($allergy->id, $userAllergies) ? 'selected' : '' }}>
                                        {{ $allergy->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                    </form>

                    @if(count($userAllergies))
                        <div class="mt-4">
                            <h6>Currently Selected:</h6>
                            @foreach($allergies->whereIn('id', $userAllergies) as $a)
                                <span class="badge badge-info mr-1 mb-1">{{ $a->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

<script>
    $(document).ready(function() {
        $('#allergies').select2({
            placeholder: 'Select your allergies',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
