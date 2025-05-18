<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Product;
use Illuminate\Http\Request;
use Recombee\RecommApi\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Recombee\RecommApi\Requests as Reqs;
use Recombee\RecommApi\Exceptions\ApiException;

class RecommendationController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client("seniorabbasandtony-dev", 'Y8nrWBcDhglVqdCSyhVx0TDgy1nm1pKJ1ORevmET8zxInlDcxGefx30MUDLSs9hg', ['region' => 'eu-west']);
    }

    public function syncProduct(Product $product)
    {
        try {
            $this->client->send(new Reqs\SetItemValues(
                (string) $product->id,
                [
                    'price' => (float) $product->price,
                    'description' => $product->description ?? '',
                    'in_stock_from' => new DateTime('now'),
                    'image' => $product->image ?? '',
                ],
                ['cascadeCreate' => true]
            ));
        } catch (ApiException $e) {
            Log::error('Recombee syncProduct failed: ' . $e->getMessage());
        }
    }
    public function syncAllProducts()
    {
        $products = Product::all();
        $requests = [];

        foreach ($products as $product) {
            $requests[] = new Reqs\SetItemValues(
                (string) $product->id,
                [
                    'price' => (float) $product->price,
                    'description' => $product->description ?? '',
                    'in_stock_from' => new DateTime('now'),
                    'image' => $product->image ?? '',
                ],
                ['cascadeCreate' => true]
            );
        }

        try {
            $this->client->send(new Reqs\Batch($requests));
            return response()->json(['message' => 'All products synced']);
        } catch (ApiException $e) {
            Log::error('Batch sync failed: ' . $e->getMessage());
            return response()->json(['error' => 'Batch sync failed'], 500);
        }
    }

    public function logView(Product $product, int $userId)
    {
        try {
            $this->syncProduct($product); // Ensure product exists in Recombee
            $this->client->send(new Reqs\AddDetailView(
                "user-$userId",
                (string) $product->id,
                ['cascadeCreate' => true]
            ));
        } catch (ApiException $e) {
            Log::error('Recombee logView failed: ' . $e->getMessage());
        }
    }

    public function logPurchase(Product $product, int $userId)
    {
        try {
            $this->syncProduct($product); // Ensure product exists in Recombee
            $this->client->send(new Reqs\AddPurchase(
                "user-$userId",
                (string) $product->id,
                ['cascadeCreate' => true]
            ));
        } catch (ApiException $e) {
            Log::error('Recombee logPurchase failed: ' . $e->getMessage());
        }
    }

    public function recommendUpsell(Product $product, int $userId)
    {
        try {
            $this->syncProduct($product);

            $recommended = $this->client->send(
                new Reqs\RecommendItemsToItem(
                    (string) $product->id,
                    "user-$userId",
                    5,
                    [
                        'filter' => "'price' > context_item[\"price\"]",
                        'cascadeCreate' => true,
                        'scenario' => 'product_detail'
                    ]
                )
            );

            return response()->json($recommended);
        } catch (ApiException $e) {
            Log::error('Recombee recommendUpsell failed: ' . $e->getMessage());
            return response()->json(['error' => 'Upsell recommendation failed.'], 500);
        }
    }

    public function search(Request $request)
    {
        $userId = Auth::id();
        $query = $request->input('q');

        if (!$userId || !$query) {
            return response()->json(['error' => 'Missing user or query'], 400);
        }

        try {
            $matches = $this->client->send(
                new Reqs\SearchItems("user-$userId", $query, 5, ['scenario' => 'search_top'])
            );

            return response()->json($matches);
        } catch (ApiException $e) {
            Log::error('Recombee search failed: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed.'], 500);
        }
    }
}
