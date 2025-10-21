<div>
    @if ($isOpen)
    <div class="modal d-block" style="background-color: rgba(0,0,0,0.5); z-index:9999;">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Supplier</h5>
                    {{-- Ganti button dengan livewire click prevent agar tidak submit form --}}
                    <button type="button" wire:click.prevent="tutup" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    {{-- Ganti wire:model.debounce menjadi wire:model.live.debounce --}}
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control mb-2"
                        placeholder="Cari supplier...">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Kontak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->idsupplier }}</td>
                                <td>{{ $supplier->nama }}</td>
                                <td>{{ $supplier->kontak }}</td>
                                <td>
                                    <button wire:click="pilih({{ $supplier->idsupplier }})"
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