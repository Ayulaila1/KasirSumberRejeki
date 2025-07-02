<div>
    @if ($isOpen)
    <div class="modal d-block" style="background-color: rgba(0,0,0,0.5); z-index:9999;">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Bahan</h5>
                    <button type="button" wire:click="tutup" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control mb-2"
                        placeholder="Cari bahan...">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bahans as $bahan)
                            <tr>
                                <td>{{ $bahan->idbahan }}</td>
                                <td>{{ $bahan->nama }}</td>
                                <td>{{ $bahan->stok }}</td>
                                <td>
                                    <button wire:click="pilih({{ $bahan->idbahan }})"
                                        class="btn btn-sm btn-primary">Pilih</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>