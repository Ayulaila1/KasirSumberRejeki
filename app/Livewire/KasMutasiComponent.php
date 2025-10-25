<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KasMutasi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KasMutasiExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class KasMutasiComponent extends Component
{
    public $tanggal, $shift, $jenis = 'masuk', $nominal, $keterangan;
    public $isOpen = false;
    public $kasMutasiList = [];

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
        $this->shift = $this->getShift();
        $this->loadKasMutasi();
    }

    public function getShift()
    {
        $hour = now()->hour;
        if ($hour >= 8 && $hour < 16)
            return 1;
        elseif ($hour >= 16 && $hour < 24)
            return 2;
        else
            return 3;
    }

    public function loadKasMutasi()
    {
        $this->kasMutasiList = KasMutasi::with(['user'])
            ->whereDate('tanggal', $this->tanggal)
            ->where('user_iduser', Auth::id()) // ✅ hanya data user yang login
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store()
    {
        $this->validate([
            'tanggal' => 'required|date',
            'shift' => 'required',
            'jenis' => 'required|in:masuk,keluar',
            'nominal' => 'required|numeric|min:1',
        ]);

        KasMutasi::create([
            'tanggal' => $this->tanggal,
            'shift' => Auth::user()->shift ?? $this->shift,
            'jenis' => $this->jenis,
            'nominal' => $this->nominal,
            'keterangan' => $this->keterangan,
            'user_iduser' => Auth::id()
        ]);

        $this->reset(['nominal', 'keterangan']);
        $this->loadKasMutasi();

        session()->flash('message', '✅ Kas berhasil disimpan!');
        $this->isOpen = false;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function exportPdf(Request $request)
    {
        $tanggalAwal = $request->input('tanggal_awal', now()->format('Y-m-d'));
        $tanggalAkhir = $request->input('tanggal_akhir', now()->format('Y-m-d'));
        $shift = $request->input('shift', '');

        $query = DB::table('kas_mutasis as k')
            ->join('users as u', 'k.user_id', '=', 'u.id')
            ->select(
                'k.tanggal',
                'k.shift',
                'k.jenis',
                'k.nominal',
                'k.keterangan',
                'u.name as user'
            )
            ->whereBetween('k.tanggal', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('k.tanggal', 'desc');

        if ($shift !== '') {
            $query->where('k.shift', $shift);
        }

        $kasMutasi = $query->get();

        $totalMasuk = $kasMutasi->where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = $kasMutasi->where('jenis', 'keluar')->sum('nominal');

        $pdf = Pdf::loadView('layouts.laporan-kas-mutasi', compact('kasMutasi', 'totalMasuk', 'totalKeluar', 'tanggalAwal', 'tanggalAkhir', 'shift'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream("Laporan-Kas-Mutasi-{$tanggalAwal}-sd-{$tanggalAkhir}.pdf");
    }

    // Excel
    public function exportExcel(Request $request)
    {
        $tanggalAwal = $request->input('tanggal_awal', now()->format('Y-m-d'));
        $tanggalAkhir = $request->input('tanggal_akhir', now()->format('Y-m-d'));
        $shift = $request->input('shift', '');

        $fileName = "Laporan-Kas-Mutasi-{$tanggalAwal}-sd-{$tanggalAkhir}.xlsx";

        return Excel::download(new KasMutasiExport($tanggalAwal, $tanggalAkhir, $shift), $fileName);
    }

    public function render()
    {
        return view('livewire.kas-mutasi-component');
    }
}
