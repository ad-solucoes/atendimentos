<?php

declare(strict_types = 1);

namespace App\Livewire\Site;

use Livewire\Component;

class Inicio extends Component
{
    public function render()
    {
        return view('livewire.site.inicio')
            ->layout('layouts.site', ['title' => 'Início']);
    }
}
