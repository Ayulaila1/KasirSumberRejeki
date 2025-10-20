<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Livewire\LaporanPenjualanComponent;
use App\Http\Controllers\Api\LaporanPenjualanController;

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API Ready!🎉'
    ]);
});

Route::post('/hello', function (Request $request) {
    return response()->json([
        'message' => 'I Love You, ' . $request->input('nama'),
    ]);
});

Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index']);
// Route::get('/laporan-penjualan', function (Request $request) {
//     $comp = new LaporanPenjualanComponent();

//     // Set nilai filter dari query params (kalau ada)
//     $comp->tglStart = $request->query('tglStart', now()->startOfMonth()->format('Y-m-d'));
//     $comp->tglEnd = $request->query('tglEnd', now()->endOfMonth()->format('Y-m-d'));
//     $comp->search = $request->query('search', '');
//     $comp->perPage = $request->query('perPage', 10);

//     // Ambil data dari fungsi render()
//     $view = $comp->render();
//     $laporan = $view->getData()['laporan'];

//     return response()->json([
//         'status' => 'success',
//         'tglStart' => $comp->tglStart,
//         'tglEnd' => $comp->tglEnd,
//         'total' => $laporan->total(),
//         'data' => $laporan->items(),
//     ]);
// });