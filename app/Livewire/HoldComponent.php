<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Hold;

class HoldComponent extends Component
{
    use WithPagination;

    public $search = '';

    public function resume($id)
    {
        $hold = Hold::findOrFail($id);

        // kirim data ke kasir (session atau redirect)
        session()->put('kasir.cart', $hold->items);
        session()->put('kasir.customer', $hold->customer);

        $hold->delete();

        return redirect()->route('kasir-component')->with('success', 'Transaksi berhasil dilanjutkan!');
    }

    public function delete($id)
    {
        Hold::findOrFail($id)->delete();
        session()->flash('success', 'Hold berhasil dihapus.');
    }

    public function render()
    {
        $holds = Hold::query()
            ->when($this->search, function ($query) {
                $query->where('customer', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.hold-component', compact('holds'));
    }

    // di HoldComponent.php
    public function lanjutkan($id)
    {
        return redirect()->route('kasir.resume', ['id' => $id]);
    }




}
