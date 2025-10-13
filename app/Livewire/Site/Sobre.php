<?php

declare(strict_types = 1);

namespace App\Livewire\Site;

use Livewire\Component;

class Sobre extends Component
{
    public function render()
    {
        return view('livewire.site.sobre')
            ->layout('layouts.site', ['title' => 'Sobre']);
    }
}
