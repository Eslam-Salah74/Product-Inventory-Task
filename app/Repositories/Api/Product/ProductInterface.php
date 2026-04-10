<?php

namespace App\Repositories\Api\Product;

interface ProductInterface
{
    public function index(array $filters = []);
    public function show(string $id);
    public function store(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
    public function adjustStock(string $id, int $amount);
    public function lowStock();
}
