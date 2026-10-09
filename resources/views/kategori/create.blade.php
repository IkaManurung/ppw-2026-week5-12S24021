<x-layout title="Tambah Kategori">

    <div class="mb-4">
        <h1 class="fw-bold">Tambah Kategori</h1>

        <p class="text-muted">
            Tambahkan kategori buku baru.
        </p>
    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form
                action="{{ route('kategori.store') }}"
                method="POST"
            >

                @csrf

                {{-- Kode Kategori --}}
                <div class="mb-3">

                    <label
                        for="kode_kategori"
                        class="form-label"
                    >
                        Kode Kategori
                    </label>

                    <input
                        type="text"
                        name="kode_kategori"
                        id="kode_kategori"
                        class="form-control @error('kode_kategori') is-invalid @enderror"
                        value="{{ old('kode_kategori') }}"
                        placeholder="Contoh: KAT-006"
                        required
                    >

                    @error('kode_kategori')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Nama Kategori --}}
                <div class="mb-3">

                    <label
                        for="nama_kategori"
                        class="form-label"
                    >
                        Nama Kategori
                    </label>

                    <input
                        type="text"
                        name="nama_kategori"
                        id="nama_kategori"
                        class="form-control @error('nama_kategori') is-invalid @enderror"
                        value="{{ old('nama_kategori') }}"
                        placeholder="Contoh: Pemrograman"
                        required
                    >

                    @error('nama_kategori')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Tombol --}}
                <div class="d-flex gap-2">

                    <a
                        href="{{ route('kategori.index') }}"
                        class="btn btn-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-del"
                    >
                        Simpan Kategori
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layout>