<?php

namespace App\Livewire;

use App\Models\Bahan;
use App\Models\Pengeluaran;
use App\Models\Pembeliandtl;
use App\Models\KasMutasi;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PengeluaranComponent extends Component
{
    use WithPagination;

    public $idpengeluaran, $bahan_idbahan, $tanggal, $jumlah, $harga, $total, $keterangan;
    public $bahanName, $stokTersedia = 0;

    public $search = '', $tglstart = '', $tglend = '', $perPage = 10;
    public $isOpen = false, $isEdit = false, $idpengeluaranToDelete;

    // ================== LIFECYCLE ==================
    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
        $this->tglstart = now()->firstOfMonth()->format('Y-m-d');
        $this->tglend = now()->format('Y-m-d');
    }

    // ================== PAGINATION RESET ==================
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingPerPage()
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

    // ================== MODAL HANDLING ==================
    public function tambahPengeluaran()
    {
        $this->resetInput();
        $this->isOpen = true;
        $this->isEdit = false;
    }

    public function close()
    {
        $this->resetInput();
        $this->dispatch('close-pengeluaran-modal');
    }

    private function resetInput()
    {
        $this->reset([
            'idpengeluaran',
            'bahan_idbahan',
            'tanggal',
            'jumlah',
            'harga',
            'total',
            'keterangan',
            'bahanName',
            'stokTersedia',
            'isOpen',
            'isEdit'
        ]);
        $this->tanggal = now()->format('Y-m-d');
        $this->resetValidation();
    }

    // ================== SIMPAN DATA ==================
    public function storePengeluaran()
    {
        $this->validate([
            'bahan_idbahan' => 'required|exists:bahans,idbahan',
            'jumlah' => 'required|numeric|min:1|max:' . $this->stokTersedia,
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $bahan = Bahan::findOrFail($this->bahan_idbahan);
            $shift = $user->shift ?? $this->getShiftFromTime(now());
            $total = $this->jumlah * ($this->harga ?? 0);

            // Simpan pengeluaran
            $pengeluaran = Pengeluaran::create([
                'bahan_idbahan' => $this->bahan_idbahan,
                'tanggal' => $this->tanggal ?? now(),
                'shift' => $shift,
                'jumlah' => $this->jumlah,
                'harga' => $this->harga ?? 0,
                'total' => $total,
                'keterangan' => $this->keterangan,
                'user_id' => $user->id, // ✅ otomatis user login
            ]);

            // Kurangi stok bahan
            $bahan->decrement('stok', $this->jumlah);

            // Catat kas keluar
            KasMutasi::create([
                'tanggal' => $this->tanggal,
                'shift' => $shift,
                'jenis' => 'keluar',
                'nominal' => $total,
                'keterangan' => 'Pengeluaran bahan operasional: ' . $bahan->nama,
                'user_id' => $user->id, // ✅ otomatis user login
            ]);

            DB::commit();

            $this->close();
            session()->flash('message', '✅ Pengeluaran berhasil disimpan oleh ' . $user->name . ' (Shift ' . $shift . ').');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // ================== SHIFT HANDLER ==================
    private function getShiftFromTime($time)
    {
        $hour = Carbon::parse($time)->hour;
        if ($hour >= 8 && $hour < 16)
            return 1;
        if ($hour >= 16 && $hour < 24)
            return 2;
        return 3; // 00:00 - 07:59
    }

    // ================== EDIT & UPDATE ==================
    public function editPengeluaran($id)
    {
        $p = Pengeluaran::findOrFail($id);
        $this->idpengeluaran = $p->idpengeluaran;
        $this->bahan_idbahan = $p->bahan_idbahan;
        $this->tanggal = $p->tanggal;
        $this->jumlah = $p->jumlah;
        $this->harga = $p->harga;
        $this->total = $p->total;
        $this->keterangan = $p->keterangan;
        $this->bahanName = optional($p->bahan)->nama;
        $this->stokTersedia = optional($p->bahan)->stok + $p->jumlah;

        $this->isEdit = true;
        $this->isOpen = true;
    }

    public function updatePengeluaran()
    {
        $p = Pengeluaran::findOrFail($this->idpengeluaran);
        $bahan = Bahan::findOrFail($this->bahan_idbahan);

        $stokEfektif = $bahan->stok + $p->jumlah;
        $this->validate(['jumlah' => 'required|numeric|min:1|max:' . $stokEfektif]);

        DB::beginTransaction();
        try {
            $selisih = $this->jumlah - $p->jumlah;
            $totalBaru = $this->jumlah * ($this->harga ?? 0);

            // Update stok & pengeluaran
            $bahan->stok -= $selisih;
            $bahan->save();

            $p->update([
                'tanggal' => $this->tanggal,
                'jumlah' => $this->jumlah,
                'harga' => $this->harga,
                'total' => $totalBaru,
                'keterangan' => $this->keterangan,
            ]);

            DB::commit();
            $this->close();
            session()->flash('message', '✅ Pengeluaran berhasil diperbarui!');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // ================== HAPUS ==================
    public function deleteConfirmationPengeluaran($id)
    {
        $this->idpengeluaranToDelete = $id;
        $this->dispatch('konfirmasi-hapus');
    }

    #[On('hapusPengeluaran')]
    public function deletePengeluaran()
    {
        $p = Pengeluaran::find($this->idpengeluaranToDelete);
        if ($p) {
            if ($p->bahan_idbahan) {
                Bahan::where('idbahan', $p->bahan_idbahan)->increment('stok', $p->jumlah);
            }
            $p->delete();
        }
        $this->dispatch('pengeluaran-disimpan', ['pesan' => 'Pengeluaran berhasil dihapus.']);
    }

    // ================== LOV BAHAN ==================
    #[On('bahanDipilih')]
    public function bahanLov($id)
    {
        $b = Bahan::find($id);
        if (!$b)
            return;

        $this->bahan_idbahan = $b->idbahan;
        $this->bahanName = $b->nama;
        $this->stokTersedia = $b->stok ?? 0;

        $hargaBeli = Pembeliandtl::where('bahan_idbahan', $b->idbahan)
            ->orderByDesc('idpembeliandtl')
            ->value('harga_beli');

        $this->harga = $hargaBeli ?? 0;
        $this->hitungTotal();
    }

    // ================== PERHITUNGAN ==================
    public function hitungTotal()
    {
        $this->total = ($this->jumlah ?? 0) * ($this->harga ?? 0);
    }
    public function updatedJumlah()
    {
        $this->hitungTotal();
    }

    // ================== LOAD DATA ==================
    public function dataPengeluaran()
    {
        return Pengeluaran::with(['bahan', 'user'])
            ->whereBetween('tanggal', [$this->tglstart, $this->tglend])
            ->when(
                $this->search,
                fn($q) =>
                $q->whereHas('bahan', fn($r) => $r->where('nama', 'like', '%' . $this->search . '%'))
            )
            ->orderBy('tanggal', 'desc')
            ->paginate($this->perPage);
    }

    // ================== EXPORT PDF ==================
    public function exportToPdf()
    {
        $headers = ['Nama Bahan', 'Tanggal', 'Jumlah', 'Harga', 'Total', 'Keterangan'];
        $title = 'Laporan Pengeluaran Operasional';
        $rows = $this->dataPengeluaran();

        $data = $rows->map(fn($r) => [
            $r->bahan->nama ?? '-',
            $r->tanggal,
            $r->jumlah,
            number_format($r->harga, 0, ',', '.'),
            number_format($r->total, 0, ',', '.'),
            $r->keterangan ?? '-'
        ]);

        $pdf = Pdf::loadView('layouts.pdf_layout', compact('data', 'headers', 'title'))
            ->setPaper('A4', 'portrait');

        return response()->streamDownload(fn() => print ($pdf->stream()), 'Pengeluaran.pdf');
    }

    // ================== RENDER ==================
    public function render()
    {
        return view('livewire.pengeluaran-component', [
            'pengeluarans' => $this->dataPengeluaran(),
        ]);
    }
}
