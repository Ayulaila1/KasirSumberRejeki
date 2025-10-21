<div>
    @if ($isOpen)
    <div class="modal d-block" style="background-color: rgba(0,0,0,0.5); z-index:9999;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Produk</h5>
                    <button type="button" wire:click="tutup" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control mb-2"
                        placeholder="Cari produk...">

                    <div class="table-responsive">
                        <table class="table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    {{-- <th>Gambar</th> --}}
                                    {{-- <th>Jenis Produk</th> --}}
                                    <th>Kategori</th>
                                    <th>Supplier</th>
                                    <th>Harga Jual</th>
                                    <th>Harga Beli</th>
                                    <th>Aksi</th>
                                    {{-- <th>Tgl Kedaluwarsa</th>
                                    <th>Stok Minimum</th>
                                    <th>Barang Titipan</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produks as $produk)
                                <tr>
                                    <td>{{ $produk->idproduk }}</td>
                                    <td>{{ $produk->nama }}</td>
                                    <td>{{ $produk->kategori }}</td>
                                    <td>{{ $produk->supplier->nama ?? '-' }}</td>
                                    <td>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</td>
                                    {{-- 4. Pindahkan tombol 'Pilih' ke dalam kolom (td) terakhir --}}
                                    <td>
                                        <button wire:click="pilih({{ $produk->idproduk }})"
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
                </div>
            </div>
        </div>
    </div>
    @endif
</div>