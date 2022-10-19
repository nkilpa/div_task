<?php

namespace App\Http\Repositories;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;

class RequestRepository
{
    public function getAll(): Collection
    {
        return Request::query()->get();
    }

    public function getById(int $id)
    {
        return Request::query()->where('id', $id)->first();
    }
}
