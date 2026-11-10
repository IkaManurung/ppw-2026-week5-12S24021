<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeminjamanResource;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Support\ApiProblem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiPeminjamanController extends Controller
{
    // Menampilkan daftar peminjaman dengan filter NIM dan status
    public function index(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'sometimes|string|max:30',
            'status' => 'sometimes|in:dipinjam,dikembalikan',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        $query = Peminjaman::with(['buku.kategori']);

        if (!empty($validated['nim'])) {
            $query->where('nim_peminjam', $validated['nim']);
        }

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $perPage = $validated['per_page'] ?? 10;

        return PeminjamanResource::collection(
            $query->latest()->paginate($perPage)
        );
    }

    // Membuat transaksi peminjaman buku
    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => 'required|integer|exists:bukus,id',
            'nim_peminjam' => 'required|string|max:30',
            'nama_peminjam' => 'required|string|max:150',
            'durasi_hari' => 'required|integer|min:1|max:30',
        ]);

        $result = DB::transaction(function () use ($validated) {

            $buku = Buku::where('id', $validated['buku_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($buku->stok < 1) {
                return [
                    'error' => true,
                    'status' => 422,
                    'message' => 'Stok buku tidak tersedia.',
                ];
            }

            $peminjamanAktif = Peminjaman::where('buku_id', $buku->id)
                ->where('nim_peminjam', $validated['nim_peminjam'])
                ->where('status', 'dipinjam')
                ->exists();

            if ($peminjamanAktif) {
                return [
                    'error' => true,
                    'status' => 409,
                    'message' => 'Mahasiswa masih memiliki peminjaman aktif untuk buku ini.',
                ];
            }

            $buku->decrement('stok');

            $peminjaman = Peminjaman::create([
                'kode_pinjam' => 'PJM-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),
                'buku_id' => $buku->id,
                'nim_peminjam' => $validated['nim_peminjam'],
                'nama_peminjam' => $validated['nama_peminjam'],
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_jatuh_tempo' => now()
                    ->addDays($validated['durasi_hari'])
                    ->toDateString(),
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);

            return [
                'error' => false,
                'data' => $peminjaman->load('buku.kategori'),
            ];
        });

        if ($result['error']) {
            $title = $result['status'] === 409
                ? 'Conflict'
                : 'Unprocessable Content';

            return ApiProblem::response(
                $result['status'],
                $title,
                $result['message']
            );
        }

        return response()->json([
            'message' => 'Peminjaman buku berhasil.',
            'data' => new PeminjamanResource($result['data']),
        ], 201, [
            'Location' => route('api.v1.peminjamans.index'),
        ]);
    }

    // Mengembalikan buku
    public function kembalikan(Peminjaman $peminjaman)
    {
        $result = DB::transaction(function () use ($peminjaman) {

            $peminjaman = Peminjaman::where('id', $peminjaman->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($peminjaman->status !== 'dipinjam') {
                return [
                    'error' => true,
                    'status' => 409,
                    'message' => 'Buku sudah dikembalikan sebelumnya.',
                ];
            }

            $buku = Buku::where('id', $peminjaman->buku_id)
                ->lockForUpdate()
                ->firstOrFail();

            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now()->toDateString(),
            ]);

            $buku->increment('stok');

            return [
                'error' => false,
                'data' => $peminjaman->load('buku.kategori'),
            ];
        });

        if ($result['error']) {
            return ApiProblem::response(
                409,
                'Conflict',
                $result['message']
            );
        }

        return response()->json([
            'message' => 'Buku berhasil dikembalikan.',
            'data' => new PeminjamanResource($result['data']),
        ], 200);
    }
}
