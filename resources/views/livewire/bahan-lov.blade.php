<div>
    @if ($isOpen)
    <div class="modal d-block" style="background-color: rgba(0,0,0,0.5); z-index:9999;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Bahan</h5>
                    <button type="button" wire:click="tutup" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control mb-2"
                        placeholder="Cari bahan...">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bahans as $bahan)
                            <tr>
                                <td>{{ $bahan->idbahan }}</td>
                                <td>{{ $bahan->nama }}</td>
                                <td>{{ $bahan->stok }}</td>
                                <td>{{ $bahan->satuan }}</td>
                                <td>{{ $bahan->jenis }}</td>
                                <td>
                                    <button wire:click="pilih({{ $bahan->idbahan }})"
                                        class="btn btn-sm btn-primary">Pilih</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                {{-- 5. Sesuaikan colspan dengan jumlah kolom header --}}
                                <td colspan="7" class="text-center text-muted">Data produk tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- 6. Tambahkan link untuk pagination --}}
                {{-- <div class="mt-3">
                    {{ $produks->links() }}
                </div> --}}
            </div>
        </div>
    </div>
    @endif
</div>