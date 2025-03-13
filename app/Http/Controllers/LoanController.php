<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::all();

        return response()->json([
            'status' => 200,
            'message' => 'Data peminjaman berhasil diambil.',
            'data' => $peminjaman
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|integer|exists:anggota,anggota_id',
            'buku_id' => 'required|integer|exists:buku,id',
            'petugas_id' => 'required|integer|exists:petugas,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'nullable|date|after_or_equal:tanggal_peminjaman',
            'status' => 'required|in:dipinjam,dikembalikan,terlambat'
        ]);

        $peminjaman = Peminjaman::create($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Peminjaman berhasil dibuat.',
            'data' => $peminjaman
        ], 200);
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => 404,
                'message' => 'Peminjaman tidak ditemukan.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Data peminjaman berhasil diambil.',
            'data' => $peminjaman
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => 404,
                'message' => 'Peminjaman tidak ditemukan.',
                'data' => null
            ], 404);
        }

        $request->validate([
            'anggota_id' => 'sometimes|integer|exists:anggota,anggota_id',
            'buku_id' => 'sometimes|integer|exists:buku,id',
            'petugas_id' => 'sometimes|integer|exists:petugas,id',
            'tanggal_peminjaman' => 'sometimes|date',
            'tanggal_pengembalian' => 'nullable|date|after_or_equal:tanggal_peminjaman',
            'status' => 'sometimes|in:dipinjam,dikembalikan,terlambat'
        ]);

        $peminjaman->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Peminjaman berhasil diperbarui.',
            'data' => $peminjaman
        ], 200);
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'status' => 404,
                'message' => 'Peminjaman tidak ditemukan.',
                'data' => null
            ], 404);
        }

        $peminjaman->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Peminjaman berhasil dihapus.',
            'data' => null
        ], 200);
    }
}
