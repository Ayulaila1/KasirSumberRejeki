<div>
    @if ($isOpen)
    <div class="modal d-block" style="background-color: rgba(0,0,0,0.5); z-index:9999;">
        <div class="modal-dialog">
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
                                    <th>Gambar</th>
                                    <th>Jenis Produk</th>
                                    <th>Kategori</th>
                                    <th>Supplier</th>
                                    <th>Harga Jual</th>
                                    <th>Harga Beli</th>
                                    <th>Tgl Kedaluwarsa</th>
                                    <th>Stok Minimum</th>
                                    <th>Barang Titipan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produks as $produk)
                                <tr>
                                    <td>{{ $produk->idproduk }}</td>
                                    <td>{{ $produk->nama }}</td>
                                    <td>{{ $produk->img }}</td>
                                    <td>{{ $produk->jenisproduk }}</td>
                                    <td>{{ $produk->kategori }}</td>
                                    <td>{{ $produk->supplier->nama ?? '-' }}</td>
                                    <td>{{ $produk->harga_beli }}</td>
                                    <td>{{ $produk->harga_jual }}</td>
                                    <td>{{ $produk->tanggal_kedaluwarsa }}</td>
                                    <td>{{ $produk->stok_minimum }}</td>
                                    <td>{{ $produk->is_titipan }}</td>
                                    <td>
                                        <button wire:click="pilih({{ $produk->idproduk }})"
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
    </div>
    @endif
</div>