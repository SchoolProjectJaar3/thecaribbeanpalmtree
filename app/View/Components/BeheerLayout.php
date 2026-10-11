<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class BeheerLayout extends Component
{
    public function __construct(
        public string $title,
        public ?string $description = null,
    ) {}

    public function render(): View
    {
        return view('layouts.beheer');
    }
}
