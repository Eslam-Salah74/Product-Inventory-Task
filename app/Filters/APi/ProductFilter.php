<?php

namespace App\Filters\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $name => $value) {
            if (method_exists($this, $name)) {
                call_user_func_array([$this, $name], array_filter([$value]));
            }
        }

        return $this->builder;
    }

    public function name($value)
    {
        $this->builder->where('name', 'like', "%{$value}%");
    }

    public function sku($value)
    {
        $this->builder->where('sku', 'like', "%{$value}%");
    }

    public function status($value)
    {
        $this->builder->where('status', $value);
    }

    public function min_price($value)
    {
        $this->builder->where('price', '>=', $value);
    }

    public function max_price($value)
    {
        $this->builder->where('price', '<=', $value);
    }
}
