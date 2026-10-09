<x-layout title="Daftar Kategori">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">Daftar Kategori</h1>

            <p class="text-muted mb-0">
                Kelola kategori buku perpustakaan.
            </p>
        </div>

        <a
            href="{{ route('kategori.create') }}"
            class="btn btn-del"
        >
            + Tambah Kategori
        </a>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            @forelse($kategoris as $index => $kategori)

                @if($loop->first)

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Kategori</th>
                                    <th>Nama Kategori</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                @endif


                                <tr>

                                    <td>
                                        {{ $kategoris->firstItem() + $index }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $kategori->kode_kategori }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $kategori->nama_kategori }}
                                    </td>

                                    <td class="text-center">

                                        <a
                                            href="{{ route('kategori.show', $kategori) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Detail
                                        </a>


                                        <a
                                            href="{{ route('kategori.edit', $kategori) }}"
                                            class="btn btn-sm btn-outline-warning"
                                        >
                                            Edit
                                        </a>


                                        <form
                                            action="{{ route('kategori.destroy', $kategori) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
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

                    <h5>Belum ada kategori</h5>

                    <p class="text-muted">
                        Belum terdapat kategori buku.
                    </p>

                    <a
                        href="{{ route('kategori.create') }}"
                        class="btn btn-del"
                    >
                        + Tambah Kategori
                    </a>

                </div>

            @endforelse

        </div>

    </div>


    {{-- Pagination --}}
    <div class="mt-4">
        {{ $kategoris->links() }}
    </div>

</x-layout>