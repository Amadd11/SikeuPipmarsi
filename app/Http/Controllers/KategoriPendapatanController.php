<?php

namespace App\Http\Controllers;

use App\Models\KategoriPendapatan;
use Illuminate\Http\Request;

class KategoriPendapatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kategoriList = KategoriPendapatan::query()
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('kategori-pendapatan.index', compact('kategoriList', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori-pendapatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:kategori_pendapatan,nama'],
        ]);

        KategoriPendapatan::create($validated);

        return redirect()->route('kategori-pendapatan.index')
            ->with('success', 'Kategori Pendapatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriPendapatan $kategoriPendapatan)
    {
        return view('kategori-pendapatan.edit', compact('kategoriPendapatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriPendapatan $kategoriPendapatan)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:kategori_pendapatan,nama,' . $kategoriPendapatan->id],
        ]);

        $kategoriPendapatan->update($validated);

        return redirect()->route('kategori-pendapatan.index')
            ->with('success', 'Kategori Pendapatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriPendapatan $kategoriPendapatan)
    {
        if ($kategoriPendapatan->pendapatan()->exists()) {
            return redirect()->route('kategori-pendapatan.index')
                ->withErrors(['general' => 'Tidak dapat menghapus kategori karena sedang digunakan pada data rencana pendapatan.']);
        }

        $kategoriPendapatan->delete();

        return redirect()->route('kategori-pendapatan.index')
            ->with('success', 'Kategori Pendapatan berhasil dihapus.');
    }
}
