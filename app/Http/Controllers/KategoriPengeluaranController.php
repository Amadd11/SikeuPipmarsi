<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;

class KategoriPengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kategoriList = KategoriPengeluaran::query()
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('kategori-pengeluaran.index', compact('kategoriList', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori-pengeluaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:kategori_pengeluaran,nama'],
        ]);

        KategoriPengeluaran::create($validated);

        return redirect()->route('kategori-pengeluaran.index')
            ->with('success', 'Kategori Pengeluaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriPengeluaran $kategoriPengeluaran)
    {
        return view('kategori-pengeluaran.edit', compact('kategoriPengeluaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriPengeluaran $kategoriPengeluaran)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:kategori_pengeluaran,nama,' . $kategoriPengeluaran->id],
        ]);

        $kategoriPengeluaran->update($validated);

        return redirect()->route('kategori-pengeluaran.index')
            ->with('success', 'Kategori Pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriPengeluaran $kategoriPengeluaran)
    {
        if ($kategoriPengeluaran->pengeluaran()->exists()) {
            return redirect()->route('kategori-pengeluaran.index')
                ->withErrors(['general' => 'Tidak dapat menghapus kategori karena sedang digunakan pada data rencana pengeluaran.']);
        }

        $kategoriPengeluaran->delete();

        return redirect()->route('kategori-pengeluaran.index')
            ->with('success', 'Kategori Pengeluaran berhasil dihapus.');
    }
}
