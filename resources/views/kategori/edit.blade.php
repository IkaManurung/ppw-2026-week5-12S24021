<x-layout title="Edit Kategori">

    <div class="mb-4">

        <h1 class="fw-bold">Edit Kategori</h1>

        <p class="text-muted">
            Perbarui informasi kategori buku.
        </p>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form
                action="{{ route('kategori.update', $kategori) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


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
                        value="{{ old('kode_kategori', $kategori->kode_kategori) }}"
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
                        value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
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
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layout>