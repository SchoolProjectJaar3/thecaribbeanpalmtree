<?php

namespace App\Http\Controllers\Beheer;

use App\Http\Controllers\Controller;
use App\Models\CmsContent;
use App\Models\CmsPage;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TekstenController extends Controller
{
    public function index(): View
    {
        return view('beheer.teksten', [
            'page' => CmsPage::byKey('home'),
            'languages' => Language::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'content' => ['required', 'array'],
            'content.*' => ['required', 'string', 'max:2000'],
        ], [
            'content.*.required' => 'Dit veld mag niet leeg zijn.',
            'content.*.max' => 'Dit veld is te lang (maximaal 2000 tekens).',
        ]);

        CmsContent::whereIn('id', array_keys($data['content']))
            ->get()
            ->each(fn(CmsContent $item) => $item->update([
                'content' => $data['content'][$item->id],
                'updated_by' => $request->user()->id,
            ]));

        return redirect()->route('beheer.teksten')
            ->with('status', 'De teksten zijn opgeslagen.');
    }
}
