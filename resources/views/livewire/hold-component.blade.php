<div class="container">
    <h4>Daftar Keranjang Hold</h4>

    <div class="mb-3">
        <input type="text" wire:model="search" class="form-control" placeholder="Cari customer...">
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($holds as $index => $hold)
            <tr>
                <td>{{ $holds->firstItem() + $index }}</td>
                <td>{{ $hold->kode_transaksi }}</td>
                <td>{{ $hold->customer ?? '-' }}</td>
                <td>Rp {{ number_format($hold->total, 0, ',', '.') }}</td>
                <td>{{ $hold->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <button wire:click="lanjutkan({{ $hold->id }})" class="btn btn-success btn-sm">
                        <i class="fas fa-play"></i> Lanjutkan
                    </button>


                    <button wire:click="$emitTo('kasir-component', 'deleteHold', {{ $hold->id }})"
                        class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </td>


            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada transaksi hold</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $holds->links() }}
</div>