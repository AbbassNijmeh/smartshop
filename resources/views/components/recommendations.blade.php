@if($recommendedProducts->isNotEmpty())
    <div class="recommendations">
        <h4>Recommended Products</h4>
        <div class="row">
            @foreach($recommendedProducts as $product)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="alert alert-info">
        No recommendations available yet. Check out our popular products:
        @foreach(App\Models\Product::inRandomOrder()->limit(5)->get() as $product)
            <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
        @endforeach
    </div>
@endif
