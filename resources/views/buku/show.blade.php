<x-layout title="Detail Buku">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">Detail Buku</h1>

            <p class="text-muted mb-0">
                Informasi lengkap buku perpustakaan.
            </p>
        </div>

        <a
            href="{{ route('buku.index') }}"
            class="btn btn-secondary"
        >
            ← Kembali
        </a>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    ISBN
                </div>

                <div class="col-md-8">
                    {{ $buku->isbn }}
                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Judul
                </div>

                <div class="col-md-8">
                    {{ $buku->judul }}
                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Penulis
                </div>

                <div class="col-md-8">
                    {{ $buku->penulis }}
                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Penerbit
                </div>

                <div class="col-md-8">
                    {{ $buku->penerbit }}
                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Tahun Terbit
                </div>

                <div class="col-md-8">
                    {{ $buku->tahun_terbit }}
                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Kategori
                </div>

                <div class="col-md-8">

                    <span class="badge text-bg-secondary">
                        {{ $buku->kategori->nama_kategori }}
                    </span>

                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Stok
                </div>

                <div class="col-md-8">
                    {{ $buku->stok }}
                </div>

            </div>


            <div class="row mb-4">

                <div class="col-md-4 fw-bold">
                    Sinopsis
                </div>

                <div class="col-md-8">

                    @if($buku->sinopsis)
                        {{ $buku->sinopsis }}
                    @else
                        <span class="text-muted">
                            Tidak ada sinopsis.
                        </span>
                    @endif

                </div>

            </div>


            <hr>


            <div class="d-flex gap-2">

                <a
                    href="{{ route('buku.edit', $buku) }}"
                    class="btn btn-warning"
                >
                    Edit Buku
                </a>


                <form
                    action="{{ route('buku.destroy', $buku) }}"
                    method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus buku ini?')"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Hapus Buku
                    </button>

                </form>

            </div>

        </div>

    </div>

</x-layout>