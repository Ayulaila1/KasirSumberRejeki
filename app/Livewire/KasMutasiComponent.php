<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KasMutasi;
use App\Exports\KasMutasiExport;

class KasMutasiComponent extends Component
{
    use WithPagination;

    public $kasMutasiId, $tanggal, $shift, $jenis = 'masuk', $nominal, $keterangan;
    public $tglstart, $tglend, $search = '', $perPage = 10;
    public $isOpen = false;
    public $isEdit = false;

    protected $listeners = ['deleteKasMutasi' => 'deleteKasMutasi'];

    // 🕓 Default inisialisasi tanggal dan shift
    public function mount()
    {
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
        $this->tanggal = now()->format('Y-m-d');
        $this->shift = $this->getShift();
    }

    // 🎯 Tentukan shift otomatis
    public function getShift()
    {
        $hour = now()->hour;
        if ($hour >= 8 && $hour < 16) {
            return 1;
        } elseif ($hour >= 16 && $hour < 24) {
            return 2;
        }
        return 3;
    }

    // 🔄 Refresh pagination ketika filter berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingTglstart()
    {
        $this->resetPage();
    }
    public function updatingTglend()
    {
        $this->resetPage();
    }
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // 📦 Ambil data kas mutasi
    public function dataKasMutasi()
    {
        return KasMutasi::with('user')
            ->whereBetween('tanggal', [$this->tglstart, $this->tglend])
            ->where('user_iduser', Auth::id())
            ->where(function ($q) {
                $q->where('keterangan', 'like', "%{$this->search}%")
                    ->orWhere('jenis', 'like', "%{$this->search}%");
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('shift', 'asc')
            ->paginate($this->perPage);
    }

    // ➕ Buka modal tambah
    public function openModal()
    {
        $this->reset(['kasMutasiId', 'tanggal', 'shift', 'jenis', 'nominal', 'keterangan']);
        $this->tanggal = now()->format('Y-m-d');
        $this->shift = $this->getShift();
        $this->isOpen = true;
        $this->isEdit = false;
    }

    // ✏️ Edit data kas mutasi
    public function editKasMutasi($id)
    {
        $kas = KasMutasi::find($id);
        if (!$kas)
            return;

        $this->kasMutasiId = $kas->id;
        $this->tanggal = $kas->tanggal;
        $this->shift = $kas->shift;
        $this->jenis = $kas->jenis;
        $this->nominal = $kas->nominal;
        $this->keterangan = $kas->keterangan;

        $this->isEdit = true;
        $this->isOpen = true;
    }

    // ❌ Tutup modal
    public function closeModal()
    {
        $this->isOpen = false;
        $this->isEdit = false;
        $this->resetValidation();
    }

    // 💾 Simpan data baru
    public function store()
    {
        $this->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:masuk,keluar',
            'nominal' => 'required|numeric|min:1',
        ]);

        KasMutasi::create([
            'tanggal' => $this->tanggal,
            'shift' => Auth::user()->shift ?? $this->shift,
            'jenis' => $this->jenis,
            'nominal' => $this->nominal,
            'keterangan' => $this->keterangan,
            'user_iduser' => Auth::id(),
        ]);

        $this->resetPage();
        $this->closeModal();

        session()->flash('message', '✅ Kas mutasi berhasil disimpan!');
    }

    // 🔁 Update data
    public function updateKasMutasi()
    {
        $this->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:masuk,keluar',
            'nominal' => 'required|numeric|min:1',
        ]);

        $kas = KasMutasi::find($this->kasMutasiId);
        if ($kas) {
            $kas->update([
                'tanggal' => $this->tanggal,
                'jenis' => $this->jenis,
                'nominal' => $this->nominal,
                'keterangan' => $this->keterangan,
            ]);

            $this->resetPage();
            session()->flash('message', '✅ Data kas mutasi berhasil diperbarui!');
        }

        $this->closeModal();
    }

    // 🗑️ Hapus data
    public function deleteKasMutasi($id)
    {
        $kas = KasMutasi::find($id);
        if ($kas) {
            $kas->delete();
            $this->resetPage();
            session()->flash('message', '🗑️ Data kas mutasi berhasil dihapus!');
        }
    }

    // 📤 Export PDF
    public function exportToPdf()
    {
        $tanggalAwal = $this->tglstart;
        $tanggalAkhir = $this->tglend;

        $kasMutasi = DB::table('kas_mutasis as k')
            ->join('users as u', 'k.user_iduser', '=', 'u.id')
            ->select('k.tanggal', 'k.shift', 'k.jenis', 'k.nominal', 'k.keterangan', 'u.name as user')
            ->whereBetween('k.tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('k.user_iduser', Auth::id())
            ->orderBy('k.tanggal', 'desc')
            ->get();

        $pdf = Pdf::loadView('layouts.laporan-kas-mutasi', [
            'kasMutasi' => $kasMutasi,
            'totalMasuk' => $kasMutasi->where('jenis', 'masuk')->sum('nominal'),
            'totalKeluar' => $kasMutasi->where('jenis', 'keluar')->sum('nominal'),
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
        ])->setPaper('A4', 'portrait');

        return response()->streamDownload(fn() => print ($pdf->stream()), "Laporan-Kas-Mutasi.pdf");
    }

    // 📊 Export Excel
    public function exportToExcel()
    {
        return Excel::download(
            new KasMutasiExport($this->tglstart, $this->tglend, Auth::user()->shift ?? null),
            'Laporan-Kas-Mutasi.xlsx'
        );
    }

    // 🖥️ Render tampilan
    public function render()
    {
        return view('livewire.kas-mutasi-component', [
            'kasMutasiList' => $this->dataKasMutasi(),
        ]);
    }
}
