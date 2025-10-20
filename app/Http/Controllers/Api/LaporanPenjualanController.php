<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $tglStart = $request->query('tglStart', now()->startOfMonth()->format('Y-m-d'));
        $tglEnd = $request->query('tglEnd', now()->endOfMonth()->format('Y-m-d'));
        $search = $request->query('search', '');
        $perPage = $request->query('perPage', 10);

        $laporan = Penjualan::with(['penjualanDtl.produk', 'user'])
            ->when($tglStart && $tglEnd, fn($q) =>
                $q->whereBetween('tanggal', [$tglStart, $tglEnd]))
            ->when($search, fn($q) =>
                $q->where(function ($q2) use ($search) {
                    $q2->where('kode_penjualan', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhereHas('penjualanDtl.produk', fn($q3) =>
                            $q3->where('nama', 'like', "%{$search}%"));
                }))
            ->orderByDesc('tanggal')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'tglStart' => $tglStart,
            'tglEnd' => $tglEnd,
            'total' => $laporan->total(),
            'data' => $laporan->items(),
        ]);
    }
}
