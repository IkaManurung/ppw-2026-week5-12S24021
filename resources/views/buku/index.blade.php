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



    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Judul Buku</p>
                    <h3>{{ $stats['total_buku'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Stok Tersedia</p>
                    <h3>{{ $stats['total_stok'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Sedang Dipinjam</p>
                    <h3>{{ $stats['sedang_dipinjam'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Sudah Dikembalikan</p>
                    <h3>{{ $stats['dikembalikan'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('buku.index') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari judul, penulis, ISBN, atau kategori..."
                        value="{{ request('search') }}"
                    >
                    <button type="submit" class="btn btn-del">
                        Cari
                    </button>
                </div>
            </form>

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
                            <th>Jumlah Peminjaman</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bukus as $index => $buku)
                            <tr>
                                <td>{{ $bukus->firstItem() + $index }}</td>
                                <td>{{ $buku->isbn }}</td>
                                <td>
                                    <strong>{{ $buku->judul }}</strong>
                                </td>
                                <td>{{ $buku->penulis }}</td>
                                <td>
                                    {{ $buku->kategori->nama_kategori }}
                                </td>
                                <td>{{ $buku->stok }}</td>
                                <td>{{ $buku->peminjamans_count }}</td>

                                <td class="text-center">
                                    <a
                                        href="{{ route('buku.show', $buku) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Detail
                                    </a>

                                    @if($buku->stok > 0)
                                        <a
                                            href="{{ route('peminjaman.create', ['buku_id' => $buku->id]) }}"
                                            class="btn btn-sm btn-outline-success"
                                        >
                                            Pinjam
                                        </a>
                                    @else
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            disabled
                                        >
                                            Stok Habis
                                        </button>
                                    @endif

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
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <h5>Belum ada data buku</h5>

                                    <p class="text-muted">
                                        @if(request('search'))
                                            Buku tidak ditemukan. Coba kata kunci lain.
                                        @else
                                            Belum terdapat data buku dalam perpustakaan.
                                        @endif
                                    </p>

                                    <a href="{{ route('buku.create') }}" class="btn btn-del">
                                        + Tambah Buku
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="mt-4">
        {{ $bukus->links() }}
    </div>

</x-layout>
