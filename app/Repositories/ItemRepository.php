<?php

namespace App\Repositories;

use App\Models\Item;
use App\Repositories\Contracts\ItemRepositoryInterface;
use Illuminate\Pagination\AbstractPaginator;

class ItemRepository implements ItemRepositoryInterface
{
    protected string $model;
    public function __construct()
    {
        $this->model = Item::class;
    }

    public function __call(string $method, array $arguments)
    {
        if (method_exists($this, $method)){
            return $this->$method(...$arguments);
        }
        return $this->model::{$method}(...$arguments);
    }

    public function paginate(string|array $columns = '*', ?int $perPage = null): AbstractPaginator
    {
        return $this->model::select($columns)
            ->latest('id')
            ->when(auth()->user()->isSupplier(), fn($q) => $q->where('supplier_id', auth()->id()))
            ->simplePaginate((int)($perPage ?? config("globals.pagination.per_page")));
    }

    public function all(string|array$columns = '*')
    {
        return $this->model::select($columns)
            ->when(auth()->user()->isSupplier(), fn($q) => $q->where('supplier_id', auth()->id()))
            ->latest('id')
            ->get();
    }

    public function show(int $id, array $with = []): Item
    {
        return $this->model::with($with)
            ->when(auth()->user()->isSupplier(), fn($q) => $q->where('supplier_id', auth()->id()))
            ->findOrFail($id);
    }

    public function store(array $data): Item
    {
        return $this->model::create($data);
    }

    public function update(int $id, array $data): Item
    {
        $item = $this->show($id);
        $item->update($data);
        return $item;
    }

    public function destroy(int $id): Item
    {
        $item = $this->show($id);
        $item->delete();
        return $item;
    }
}
