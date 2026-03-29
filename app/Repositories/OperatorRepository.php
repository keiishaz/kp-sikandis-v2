<?php

namespace App\Repositories;

use App\Contracts\Repositories\OperatorRepositoryInterface;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class OperatorRepository implements OperatorRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $operatorRole = Role::where('nama_role', 'operator')->firstOrFail();
        $query = User::where('role_id', $operatorRole->id);

        if (! empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%")
                        ->orWhere('nip', 'like', "%{$q}%");
            });
        }

        $validSorts = ['name', 'nik', 'nip'];
        $sort = in_array($filters['sort'] ?? null, $validSorts) ? $filters['sort'] : 'name';
        $dir  = ($filters['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

        return $query->orderBy($sort, $dir)->paginate($perPage)->withQueryString();
    }

    public function create(array $data): User
    {
        $operatorRole = Role::where('nama_role', 'operator')->firstOrFail();

        return User::create([
            'name'     => $data['name'],
            'nik'      => $data['nik'],
            'nip'      => $data['nip'],
            'password' => Hash::make($data['password']),
            'role_id'  => $operatorRole->id,
        ]);
    }

    public function update(User $operator, array $data): User
    {
        $operator->name = $data['name'];
        $operator->nik  = $data['nik'];
        $operator->nip  = $data['nip'];

        if (! empty($data['password'])) {
            $operator->password = Hash::make($data['password']);
        }

        $operator->save();

        return $operator->fresh();
    }

    public function delete(User $operator): void
    {
        $operator->delete();
    }
}
