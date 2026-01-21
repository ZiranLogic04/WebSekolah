<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::query()->where('status_aktif', 'Aktif');

        // Filter Subject
        if ($request->filled('subject')) {
            $query->where('bidang_studi', 'like', '%' . $request->subject . '%');
        }

        // Filter Role
        if ($request->filled('role')) {
            $query->where('jabatan', 'like', '%' . $request->role . '%');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('bidang_studi', 'like', "%{$search}%");
            });
        }

        $teachers = $query->orderBy('nama')->paginate(12)->withQueryString();

        // Get unique subjects and roles for filters
        $subjects = Guru::where('status_aktif', 'Aktif')
                        ->select('bidang_studi')
                        ->distinct()
                        ->whereNotNull('bidang_studi')
                        ->orderBy('bidang_studi')
                        ->pluck('bidang_studi');

        $roles = Guru::where('status_aktif', 'Aktif')
                     ->select('jabatan')
                     ->distinct()
                     ->whereNotNull('jabatan')
                     ->orderBy('jabatan')
                     ->pluck('jabatan');

        return Inertia::render('Guru/Index', [
            'teachers' => $teachers,
            'subjects' => $subjects,
            'roles' => $roles,
            'filters' => $request->only(['search', 'subject', 'role'])
        ]);
    }

    public function show($slug)
    {
        // For now, redirect to index or show detail modal if needed
        // Assuming slug is based on name for now, but better use ID or add slug column
        // Since we don't have slug column, maybe just ignore or basic find by name
        // But for this task, the main requirement is the Directory Grid.
        return redirect()->route('guru.public.index');
    }
}
