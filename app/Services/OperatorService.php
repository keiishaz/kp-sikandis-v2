<?php

namespace App\Services;

use App\Contracts\Repositories\OperatorRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OperatorService
{
    public function __construct(
        private readonly OperatorRepositoryInterface $operatorRepo,
    ) {}

    public function paginatedList(array $filters): LengthAwarePaginator
    {
        return $this->operatorRepo->paginate($filters);
    }

    public function create(array $data): User
    {
        return $this->operatorRepo->create($data);
    }

    public function update(User $operator, array $data): User
    {
        return $this->operatorRepo->update($operator, $data);
    }

    public function delete(User $operator): void
    {
        $this->operatorRepo->delete($operator);
    }
}
