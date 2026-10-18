<x-layout title="Daftar Peminjaman">

    <h1 class="fw-bold mb-2">Daftar Peminjaman</h1>
    <p class="text-muted mb-4">
        Data transaksi peminjaman dan pengembalian buku.
    </p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Kode Pinjam</th>
                            <th>Judul Buku</th>
                            <th>NIM</th>
                            <th>Nama Peminjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($peminjamans as $peminjaman)
                            <tr>
                                <td>{{ $peminjaman->kode_pinjam }}</td>
                                <td>{{ $peminjaman->buku->judul }}</td>
                                <td>{{ $peminjaman->nim_peminjam }}</td>
                                <td>{{ $peminjaman->nama_peminjam }}</td>
                                <td>{{ $peminjaman->tanggal_pinjam }}</td>
                                <td>{{ $peminjaman->tanggal_jatuh_tempo }}</td>
                                <td>{{ $peminjaman->status }}</td>
                                <td>
                                    @if($peminjaman->status === 'dipinjam')
                                        <form
                                            action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Konfirmasi pengembalian buku?')"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-success"
                                            >
                                                Kembalikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    Belum ada transaksi peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('buku.index') }}" class="btn btn-secondary">
                Kembali ke Daftar Buku
            </a>
        </div>
    </div>

</x-layout>
