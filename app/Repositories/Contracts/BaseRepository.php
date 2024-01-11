<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractPaginator;

interface BaseRepository
{
    public function __call(string $method, array $arguments);

    public function paginate(string|array$columns = '*', ?int $perPage = null): AbstractPaginator;
    public function all(string|array $columns = '*');
    public function show(int $id, array $with = []):Model;
    public function store(array $data):Model;
    public function update(int $id, array $data):Model;
    public function destroy(int $id):Model;
}
