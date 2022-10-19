<?php

namespace App\Repositories\Impls;

use App\Dto\RequestFilterDto;
use App\Models\Request;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RequestRepository implements RequestRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getAll(RequestFilterDto $dto): Collection|null
    {
        $query = Request::query();

        if (!is_null($dto->date))
        {
            $query->whereDate('created_at', $dto->date);
        }
        if (!is_null($dto->status))
        {
            $query->where('status', '=', $dto->status);
        }

        return $query->orderBy('id')->get();
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): Model|Request|null
    {
        return Request::query()->find($id);
    }
}
