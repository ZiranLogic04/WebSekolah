<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::orderBy('order', 'asc')->get();
        return \Inertia\Inertia::render('Admin/Tampilan/Slider', [
            'sliders' => $sliders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_position' => 'nullable|integer|min:0|max:100',
            'top_badge' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ], [
            'image.required' => 'Gambar wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'image_position.integer' => 'Posisi gambar harus berupa angka.',
            'image_position.min' => 'Posisi gambar minimal 0.',
            'image_position.max' => 'Posisi gambar maksimal 100.',
            'top_badge.max' => 'Badge maksimal 50 karakter.',
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'subtitle.required' => 'Deskripsi wajib diisi.',
            'subtitle.max' => 'Deskripsi maksimal 255 karakter.',
            'button_text.max' => 'Teks tombol maksimal 50 karakter.',
            'button_url.max' => 'URL tombol maksimal 255 karakter.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.min' => 'Urutan minimal 0.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid() . '.webp';
            $imagePath = 'sliders/' . $filename;
            
            // Ensure directory exists
            if (!Storage::disk('public')->exists('sliders')) {
                Storage::disk('public')->makeDirectory('sliders');
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->toWebp(quality: 80)->save(storage_path('app/public/' . $imagePath));
        }

        Slider::create([
            'image' => $imagePath,
            'image_position' => $request->image_position ?? 50,
            'top_badge' => $request->top_badge,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->back()->with('success', 'Slider berhasil ditambahkan (Auto Convert WebP)');
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_position' => 'nullable|integer|min:0|max:100',
            'top_badge' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ], [
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'image_position.integer' => 'Posisi gambar harus berupa angka.',
            'image_position.min' => 'Posisi gambar minimal 0.',
            'image_position.max' => 'Posisi gambar maksimal 100.',
            'top_badge.max' => 'Badge maksimal 50 karakter.',
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'subtitle.required' => 'Deskripsi wajib diisi.',
            'subtitle.max' => 'Deskripsi maksimal 255 karakter.',
            'button_text.max' => 'Teks tombol maksimal 50 karakter.',
            'button_url.max' => 'URL tombol maksimal 255 karakter.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.min' => 'Urutan minimal 0.',
        ]);

        $data = [
            'image_position' => $request->image_position ?? $slider->image_position,
            'top_badge' => $request->top_badge,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'order' => $request->order,
            'is_active' => $request->is_active,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }

            $file = $request->file('image');
            $filename = uniqid() . '.webp';
            $imagePath = 'sliders/' . $filename;
            
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->toWebp(quality: 80)->save(storage_path('app/public/' . $imagePath));

            $data['image'] = $imagePath;
        }

        $slider->update($data);

        return redirect()->back()->with('success', 'Slider berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        if ($slider->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($slider->image);
        }
        $slider->delete();

        return redirect()->back()->with('success', 'Slider berhasil dihapus');
    }
}
