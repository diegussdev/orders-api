<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = 10;
        $sales = Sale::with('products')->paginate($perPage, ['*'], 'page', $page);

        $response = collect();
        foreach ($sales as $sale) {
            $sale->products->each(function ($product) {
                $product->makeHidden(['created_at', 'updated_at']);
                $product->unsetRelation('pivot');
            });

            $response->push($sale);
        }

        return response()->json([
            'data' => $response,
            'current_page' => $sales->currentPage(),
            'per_page' => $sales->perPage(),
            'total' => $sales->total(),
        ]);
    }

    public function show(Sale $sale)
    {
        $sale->load('products');

        $sale->products->each(function ($product) {
            $product->makeHidden(['created_at', 'updated_at']);
            $product->unsetRelation('pivot');
        });

        return response()->json($sale, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sale = Sale::create([
            'amount' => $request->amount,
            'status' => Sale::STATUS_CONCLUDED,
        ]);

        $sale->products()->attach(collect($request->products)->pluck('id')->toArray());

        $sale->load('products')
            ->products
            ->each(function ($product) {
                $product->makeHidden(['created_at', 'updated_at']);
                $product->unsetRelation('pivot');
            });

        return response()->json($sale, 201);
    }

    public function cancel(Request $request, Sale $sale)
    {
        $sale->update(['status' => Sale::STATUS_CANCELED]);

        $sale->load('products')
            ->products
            ->each(function ($product) {
                $product->makeHidden(['created_at', 'updated_at']);
                $product->unsetRelation('pivot');
            });

        return response()->json($sale, 200);
    }
}
