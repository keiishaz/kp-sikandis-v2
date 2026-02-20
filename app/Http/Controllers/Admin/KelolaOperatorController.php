<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelolaOperatorController extends Controller
{
    public function index(Request $request)
    {
        $operatorRole = Role::where('nama_role', 'operator')->firstOrFail();

        $query = User::where('role_id', $operatorRole->id);

        if ($q = $request->input('q')) {
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('nip', 'like', "%{$q}%");
            });
        }

        $validSorts = ['name', 'nip'];
        $sort = in_array($request->input('sort'), $validSorts) ? $request->input('sort') : 'name';
        $dir  = $request->input('dir') === 'desc' ? 'desc' : 'asc';

        $operators = $query->orderBy($sort, $dir)->paginate(15)->withQueryString();

        return view('admin.kelola-operator.index', compact('operators'));
    }

    public function store(Request $request)
    {
        $operatorRole = Role::where('nama_role', 'operator')->firstOrFail();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'nip'      => ['required', 'string', 'max:30', 'unique:users,nip'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name'     => $data['name'],
            'nip'      => $data['nip'],
            'password' => Hash::make($data['password']),
            'role_id'  => $operatorRole->id,
        ]);

        return redirect()->route('admin.kelola-operator.index')
                         ->with('success', 'Operator berhasil ditambahkan.');
    }

    public function edit(User $kelola_operator)
    {
        return view('admin.kelola-operator.edit', ['operator' => $kelola_operator]);
    }

    public function update(Request $request, User $kelola_operator)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'nip'      => ['required', 'string', 'max:30', Rule::unique('users', 'nip')->ignore($kelola_operator->id)],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $kelola_operator->name = $data['name'];
        $kelola_operator->nip  = $data['nip'];

        if (!empty($data['password'])) {
            $kelola_operator->password = Hash::make($data['password']);
        }

        $kelola_operator->save();

        return redirect()->route('admin.kelola-operator.index')
                         ->with('success', 'Operator berhasil diperbarui.');
    }

    public function destroy(User $kelola_operator)
    {
        $kelola_operator->delete();

        return redirect()->route('admin.kelola-operator.index')
                         ->with('success', 'Operator berhasil dihapus.');
    }
}
