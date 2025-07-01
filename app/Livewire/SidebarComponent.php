<?php

namespace App\Livewire;

use Livewire\Component;

class SidebarComponent extends Component
{
    public $currentPage = 'dashboard';

    public $showSidebar = false;
    public $showDropdown = false;


    public function toggleSidebar()
    {
        $this->showSidebar = !$this->showSidebar;
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function navigateTo($page)
    {
        $this->currentPage = $page;
        $this->showSidebar = false;
    }

    public function render()
    {
        return view('livewire.sidebar-component');
    }
}
