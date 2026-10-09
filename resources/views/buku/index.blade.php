<x-layout title="Daftar Buku">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Daftar Buku</h1>
            <p class="text-muted mb-0">
                Sistem Informasi Perpustakaan Kampus Del
            </p>
        </div>

        <a href="{{ route('buku.create') }}" class="btn btn-del">
            + Tambah Buku
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @forelse($bukus as $index => $buku)

                @if($loop->first)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>ISBN</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                @endif

                                <tr>
                                    <td>
                                        {{ $bukus->firstItem() + $index }}
                                    </td>

                                    <td>
                                        {{ $buku->isbn }}
                                    </td>

                                    <td>
                                        <strong>{{ $buku->judul }}</strong>
                                    </td>

                                    <td>
                                        {{ $buku->penulis }}
                                    </td>

                                    <td>
                                        <span class="badge text-bg-secondary">
                                            {{ $buku->kategori->nama_kategori }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $buku->stok }}
                                    </td>

                                    <td class="text-center">

                                        <a
                                            href="{{ route('buku.show', $buku) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="{{ route('buku.edit', $buku) }}"
                                            class="btn btn-sm btn-outline-warning"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('buku.destroy', $buku) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus buku ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                Hapus
                                            </button>
                                        </form>

                                    </td>
                                </tr>

                @if($loop->last)
                            </tbody>

                        </table>
                    </div>
                @endif

            @empty

                <div class="text-center py-5">
                    <h5>Belum ada data buku</h5>

                    <p class="text-muted">
                        Belum terdapat data buku dalam perpustakaan.
                    </p>

                    <a
                        href="{{ route('buku.create') }}"
                        class="btn btn-del"
                    >
                        + Tambah Buku
                    </a>
                </div>

            @endforelse

        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $bukus->links() }}
    </div>

</x-layout>