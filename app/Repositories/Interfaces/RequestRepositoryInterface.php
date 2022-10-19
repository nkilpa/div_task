<?php

namespace App\Repositories\Interfaces;

use App\Dto\RequestFilterDto;
use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RequestRepositoryInterface
{
    /**
     * Возвращает список заявок из БД.
     *
     * @param RequestFilterDto $dto
     * @return Collection|null
     */
    public function getAll(RequestFilterDto $dto): Collection|null;

    /**
     * Возвращает заявку из БД по id
     *
     * @param int $id
     * @return Model|Request|null
     */
    public function getById(int $id): Model|Request|null;
}
