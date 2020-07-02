<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'preview-id',
        'preview-harga',
        'preview-id-pelanggan',
        'lists/produk-cabang',
        'lists/cabang',
        'admin-produk/kode-produk'
    ];
}
