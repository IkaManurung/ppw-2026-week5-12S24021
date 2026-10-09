<x-layout title="Detail Kategori">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">Detail Kategori</h1>

            <p class="text-muted mb-0">
                Informasi kategori buku.
            </p>
        </div>

        <a
            href="{{ route('kategori.index') }}"
            class="btn btn-secondary"
        >
            ← Kembali
        </a>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Kode Kategori
                </div>

                <div class="col-md-8">
                    {{ $kategori->kode_kategori }}
                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Nama Kategori
                </div>

                <div class="col-md-8">
                    {{ $kategori->nama_kategori }}
                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Jumlah Buku
                </div>

                <div class="col-md-8">
                    {{ $kategori->bukus()->count() }} buku
                </div>

            </div>


            <hr>


            <div class="d-flex gap-2">

                <a
                    href="{{ route('kategori.edit', $kategori) }}"
                    class="btn btn-warning"
                >
                    Edit Kategori
                </a>

                <form
                    action="{{ route('kategori.destroy', $kategori) }}"
                    method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Hapus Kategori
                    </button>

                </form>

            </div>

        </div>

    </div>

</x-layout>