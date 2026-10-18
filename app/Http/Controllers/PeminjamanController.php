<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class PeminjamanController extends Controller
{
    public function index()
{
    $peminjamans = Peminjaman::with('buku')
        ->latest()
        ->get();

    return view('peminjaman.index', compact('peminjamans'));
}

    public function create(Request $request)
{
    $bukus = Buku::orderBy('judul')->get();

    return view('peminjaman.create', compact('bukus'));
}
    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim_peminjam' => 'required|string|max:255',
            'nama_peminjam' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $buku = Buku::where('id', $validated['buku_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($buku->stok < 1) {
                    throw new \RuntimeException('Stok buku habis.');
                }

                $sudahMeminjam = Peminjaman::where(
                    'buku_id',
                    $buku->id
                )
                    ->where('nim_peminjam', $validated['nim_peminjam'])
                    ->where('status', 'dipinjam')
                    ->exists();

                if ($sudahMeminjam) {
                    throw new \RuntimeException(
                        'Buku ini masih dipinjam oleh NIM tersebut.'
                    );
                }

                $buku->decrement('stok');

                Peminjaman::create([
                    'kode_pinjam' => 'PJM-' . uniqid(),
                    'buku_id' => $buku->id,
                    'nim_peminjam' => $validated['nim_peminjam'],
                    'nama_peminjam' => $validated['nama_peminjam'],
                    'tanggal_pinjam' => $validated['tanggal_pinjam'],
                    'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
                    'status' => 'dipinjam',
                ]);
            });

            return back()->with('success', 'Peminjaman buku berhasil.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            Log::error('Gagal menyimpan peminjaman: ' . $e->getMessage());

            return back()->withInput()->with(
                'error',
                'Peminjaman gagal disimpan.'
            );
        }
    }

    public function kembalikan($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $peminjaman = Peminjaman::where('id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($peminjaman->status === 'dikembalikan') {
                    throw new \RuntimeException(
                        'Buku ini sudah dikembalikan.'
                    );
                }

                $buku = Buku::where('id', $peminjaman->buku_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $peminjaman->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);

                $buku->increment('stok');
            });

            return back()->with('success', 'Buku berhasil dikembalikan.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            Log::error('Gagal mengembalikan buku: ' . $e->getMessage());

            return back()->with('error', 'Pengembalian buku gagal.');
        }
    }
}
