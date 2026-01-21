<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeatureController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Tampilan/Features', [
            'features' => Feature::orderBy('order')->get(),
            'availableIcons' => Feature::getAvailableIcons(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'features' => 'required|array|size:4',
            'features.*.id' => 'required|exists:features,id',
            'features.*.icon' => 'required|string',
            'features.*.title' => 'required|string|max:100',
            'features.*.description' => 'required|string|max:500',
            'features.*.is_active' => 'boolean',
        ], [
            'features.*.icon.required' => 'Icon wajib dipilih.',
            'features.*.title.required' => 'Judul feature wajib diisi.',
            'features.*.title.max' => 'Judul maksimal 100 karakter.',
            'features.*.description.required' => 'Deskripsi feature wajib diisi.',
            'features.*.description.max' => 'Deskripsi maksimal 500 karakter.',
        ]);

        foreach ($request->features as $index => $featureData) {
            Feature::where('id', $featureData['id'])->update([
                'icon' => $featureData['icon'],
                'title' => $featureData['title'],
                'description' => $featureData['description'],
                'is_active' => $featureData['is_active'] ?? true,
                'order' => $index + 1,
            ]);
        }

        return redirect()->back()->with('success', 'Feature berhasil diperbarui!');
    }
}
