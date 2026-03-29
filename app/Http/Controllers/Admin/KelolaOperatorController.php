<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OperatorService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelolaOperatorController extends Controller
{
    public function __construct(private readonly OperatorService $operatorService) {}

    public function index(Request $request)
    {
        $operators = $this->operatorService->paginatedList($request->only(['q', 'sort', 'dir']));

        return view('admin.kelola-operator.index', compact('operators'));
    }

    public function create()
    {
        return view('admin.kelola-operator.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'nik'      => ['required', 'numeric', 'digits:16', 'unique:users,nik'],
            'nip'      => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $this->operatorService->create($data);

        return redirect()->route('admin.kelola-operator.index')
                         ->with('success', 'Operator berhasil ditambahkan.');
    }

    public function show(User $kelola_operator)
    {
        return view('admin.kelola-operator.show', ['operator' => $kelola_operator]);
    }

    public function edit(User $kelola_operator)
    {
        return view('admin.kelola-operator.edit', ['operator' => $kelola_operator]);
    }

    public function update(Request $request, User $kelola_operator)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'nik'      => ['required', 'numeric', 'digits:16', Rule::unique('users', 'nik')->ignore($kelola_operator->id)],
            'nip'      => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $this->operatorService->update($kelola_operator, $data);

        return redirect()->route('admin.kelola-operator.index')
                         ->with('success', 'Operator berhasil diperbarui.');
    }

    public function destroy(User $kelola_operator)
    {
        $this->operatorService->delete($kelola_operator);

        return redirect()->route('admin.kelola-operator.index')
                         ->with('success', 'Operator berhasil dihapus.');
    }
}
