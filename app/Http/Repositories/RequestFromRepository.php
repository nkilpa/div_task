<?php

namespace App\Http\Repositories;

use App\Models\RequestForm;
use Illuminate\Database\Eloquent\Collection;

class RequestFromRepository
{
    public function getAll(): Collection
    {
        return RequestForm::query()->get();
    }

    public function getById(int $id)
    {
        return RequestForm::query()->where('id', $id)->first();
    }
}
