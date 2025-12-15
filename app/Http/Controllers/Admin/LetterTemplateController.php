<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LetterTemplate;
use Inertia\Inertia;

class LetterTemplateController extends Controller
{
    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required',
        ]);

        LetterTemplate::create($request->all());

        return redirect()->back()->with('success', 'Template berhasil dibuat.');
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, LetterTemplate $letterTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $letterTemplate->update($request->all());

        return redirect()->back()->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy(LetterTemplate $letterTemplate)
    {
        $letterTemplate->delete();
        return redirect()->back()->with('success', 'Template berhasil dihapus.');
    }
}
