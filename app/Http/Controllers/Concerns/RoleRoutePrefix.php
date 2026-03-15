<?php

namespace App\Http\Controllers\Concerns;

/**
 * Trait RoleRoutePrefix
 * Menyediakan helper `rp()` yang mengembalikan prefix route
 * ('admin' atau 'operator') berdasarkan role user yang sedang login.
 * Gunakan pada controller yang dibagi antara Admin dan Operator.
 */
trait RoleRoutePrefix
{
    /**
     * Mengembalikan route prefix berdasarkan role user yang login.
     *
     * @return string 'admin' | 'operator'
     */
    protected function rp(): string
    {
        return auth()->user()?->role?->nama_role === 'operator' ? 'operator' : 'admin';
    }
}
