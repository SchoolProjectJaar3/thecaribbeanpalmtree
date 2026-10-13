<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;

class PaginaController extends Controller
{
    public function home()
    {
        return view('pages.home', ['accommodation' => $this->accommodation()]);
    }

    public function hetHuis()
    {
        return view('pages.het-huis', ['accommodation' => $this->accommodation()]);
    }

    private function accommodation(): ?Accommodation
    {
        return Accommodation::with([
            'translations.language',
            'facilities' => fn ($q) => $q->orderBy('sort_order')->with('translations.language'),
        ])->where('is_active', true)->orderBy('id')->first();
    }
}