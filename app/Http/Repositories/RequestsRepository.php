<?php

namespace App\Http\Repositories;

use App\Models\Requests;
use Illuminate\Database\Eloquent\Collection;

class RequestsRepository
{
    public function getAll(): Collection
    {
        return Requests::query()->get();
    }

    public function getById(int $id)
    {
        return Requests::query()->where('id', $id)->first();
    }
}
