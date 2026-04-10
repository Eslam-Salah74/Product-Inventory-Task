<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Repositories\Api\Product\ProductInterface;
use App\Resources\Product\ProductResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;

    protected $productRepository;

    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $products = $this->productRepository->index($request->all());
        return $this->apiResponse(ProductResource::collection($products), 'Products retrieved successfully');
    }

    public function show($id)
    {
        $product = $this->productRepository->show($id);
        return $this->apiResponse(new ProductResource($product), 'Product retrieved successfully');
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productRepository->store($request->validated());
        return $this->apiResponse(new ProductResource($product), 'Product created successfully', 201);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->productRepository->update($id, $request->validated());
        return $this->apiResponse(new ProductResource($product), 'Product updated successfully');
    }

    public function destroy($id)
    {
        $this->productRepository->delete($id);
        return $this->apiResponse(null, 'Product deleted successfully', 204);
    }

    public function adjustStock(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|integer'
        ]);

        $product = $this->productRepository->adjustStock($id, $request->amount);
        return $this->apiResponse(new ProductResource($product), 'Stock adjusted successfully');
    }

    public function lowStock()
    {
        $products = $this->productRepository->lowStock();
        return $this->apiResponse(ProductResource::collection($products), 'Low stock products retrieved successfully');
    }
}
