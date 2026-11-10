<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class ApiBukuController extends Controller
{
    /**
     * Menampilkan daftar buku.
     * Mendukung pencarian, filter, sorting, dan pagination.
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans');

        // Pencarian berdasarkan judul, penulis, atau ISBN
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;

            $q->where(function ($sub) use ($search) {
                $sub->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        });

        // Filter kategori
        $query->when($request->filled('kategori_id'), function ($q) use ($request) {
            $q->where('kategori_id', $request->kategori_id);
        });

        // Filter buku yang tersedia
        if ($request->input('tersedia') == 1) {
            $query->where('stok', '>', 0);
        }

        // Sorting
        $sort = $request->input('sort', 'terbaru');

        if ($sort === 'judul') {
            $query->orderBy('judul');
        } elseif ($sort === 'stok') {
            $query->orderByDesc('stok');
        } else {
            $query->latest();
        }

        // Pagination maksimal 100 data
        $perPage = min(
            max((int) $request->input('per_page', 10), 1),
            100
        );

        return BukuResource::collection(
            $query->paginate($perPage)
        );
    }

    /**
     * Menampilkan detail buku beserta kategori dan riwayat peminjaman.
     */
    public function show(Buku $buku)
    {
        $buku->load([
            'kategori',
            'peminjamans',
        ]);

        $buku->loadCount('peminjamans');

        return new BukuResource($buku);
    }

    /**
     * Consumer API:
     * Mengambil referensi buku dari OpenLibrary.
     * Pencarian dilakukan berdasarkan ISBN terlebih dahulu,
     * kemudian menggunakan judul sebagai fallback.
     */
    public function referensiEksternal(Buku $buku)
    {
        try {
            $referensi = null;

            // 1. Pencarian berdasarkan ISBN
            if (!empty($buku->isbn)) {
                $response = Http::acceptJson()
                    ->connectTimeout(5)
                    ->timeout(10)
                    ->get('https://openlibrary.org/api/books', [
                        'bibkeys' => 'ISBN:' . trim($buku->isbn),
                        'format' => 'json',
                        'jscmd' => 'data',
                    ]);

                if (!$response->successful()) {
                    return $this->openLibraryError();
                }

                $data = $response->json();

                $referensi = $data['ISBN:' . trim($buku->isbn)] ?? null;
            }

            // 2. Jika ISBN tidak ditemukan, cari berdasarkan judul
            if (!$referensi) {
                $response = Http::acceptJson()
                    ->connectTimeout(5)
                    ->timeout(10)
                    ->get('https://openlibrary.org/search.json', [
                        'title' => $buku->judul,
                        'author' => $buku->penulis,
                        'limit' => 5,
                    ]);

                if (!$response->successful()) {
                    return $this->openLibraryError();
                }

                $results = $response->json('docs', []);

                if (!empty($results)) {
                    // Utamakan judul yang sama persis
                    foreach ($results as $item) {
                        if (
                            isset($item['title']) &&
                            strcasecmp(
                                trim($item['title']),
                                trim($buku->judul)
                            ) === 0
                        ) {
                            $referensi = $item;
                            break;
                        }
                    }

                    // Jika tidak ada yang sama persis,
                    // gunakan hasil pencarian pertama.
                    $referensi = $referensi ?? $results[0];
                }
            }

            // 3. Jika referensi tidak ditemukan
            if (!$referensi) {
                return response()->json([
                    'message' => 'Referensi buku tidak ditemukan di OpenLibrary.',
                    'source' => 'OpenLibrary',
                    'data' => null,
                ], 200);
            }

            // 4. Format hasil referensi
            return response()->json([
                'message' => 'Referensi eksternal berhasil ditemukan.',
                'source' => 'OpenLibrary',
                'data' => [
                    'judul' => $referensi['title'] ?? null,
                    'penulis' => $referensi['authors']
                        ?? $referensi['author_name']
                        ?? [],
                    'tahun_terbit' => $referensi['publish_date']
                        ?? $referensi['first_publish_year']
                        ?? null,
                    'isbn' => $referensi['identifiers']['isbn']
                        ?? $referensi['isbn']
                        ?? [],
                    'edition_key' => $referensi['key'] ?? null,
                    'url' => isset($referensi['url'])
                        ? $referensi['url']
                        : (
                            isset($referensi['key'])
                                ? 'https://openlibrary.org' . $referensi['key']
                                : null
                        ),
                ],
            ], 200);

        } catch (ConnectionException $exception) {
            return response()->json([
                'type' => 'about:blank',
                'title' => 'Service Unavailable',
                'status' => 503,
                'detail' => 'Layanan OpenLibrary sementara tidak dapat dijangkau.',
            ], 503)->header(
                'Content-Type',
                'application/problem+json'
            );
        }
    }

    /**
     * Menghasilkan respons ketika OpenLibrary gagal.
     */
    private function openLibraryError()
    {
        return response()->json([
            'type' => 'about:blank',
            'title' => 'Bad Gateway',
            'status' => 502,
            'detail' => 'Layanan OpenLibrary gagal memberikan respons.',
        ], 502)->header(
            'Content-Type',
            'application/problem+json'
        );
    }

    /**
     * Menambahkan buku baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn' => 'required|string|max:20|unique:bukus,isbn',
            'judul' => 'required|string|min:5|max:200',
            'penulis' => 'required|string|max:150',
            'penerbit' => 'required|string|max:150',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',
        ]);

        $buku = Buku::create($validated);

        $buku->load('kategori');
        $buku->loadCount('peminjamans');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => new BukuResource($buku),
        ], 201)->header(
            'Location',
            route('api.v1.bukus.show', $buku->id)
        );
    }

    /**
     * Mengubah seluruh data buku (PUT).
     */
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'isbn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('bukus', 'isbn')->ignore($buku->id),
            ],
            'judul' => 'required|string|min:5|max:200',
            'penulis' => 'required|string|max:150',
            'penerbit' => 'required|string|max:150',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',
        ]);

        $buku->update($validated);

        $buku->load('kategori');
        $buku->loadCount('peminjamans');

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => new BukuResource($buku),
        ], 200);
    }

    /**
     * Mengubah sebagian data buku (PATCH).
     */
    public function patch(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'isbn' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('bukus', 'isbn')->ignore($buku->id),
            ],
            'judul' => 'sometimes|required|string|min:5|max:200',
            'penulis' => 'sometimes|required|string|max:150',
            'penerbit' => 'sometimes|required|string|max:150',
            'tahun_terbit' => 'sometimes|required|integer|min:1900|max:' . date('Y'),
            'kategori_id' => 'sometimes|required|exists:kategoris,id',
            'stok' => 'sometimes|required|integer|min:0',
            'sinopsis' => 'sometimes|nullable|string',
        ]);

        $buku->update($validated);

        $buku->load('kategori');
        $buku->loadCount('peminjamans');

        return response()->json([
            'message' => 'Buku berhasil diperbarui sebagian.',
            'data' => new BukuResource($buku),
        ], 200);
    }

    /**
     * Menghapus buku.
     * Buku tidak dapat dihapus jika masih memiliki peminjaman aktif.
     */
    public function destroy(Buku $buku)
    {
        $activeLoans = $buku->peminjamans()
            ->where('status', 'dipinjam')
            ->exists();

        if ($activeLoans) {
            return response()->json([
                'message' => 'Buku tidak dapat dihapus karena masih memiliki peminjaman aktif.',
            ], 409);
        }

        $buku->delete();

        return response()->json(null, 204);
    }
}
