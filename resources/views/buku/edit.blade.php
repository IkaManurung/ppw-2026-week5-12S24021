<x-layout title="Edit Buku">

    <div class="mb-4">
        <h1 class="fw-bold">Edit Buku</h1>

        <p class="text-muted">
            Perbarui informasi buku perpustakaan.
        </p>
    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form
                action="{{ route('buku.update', $buku) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- ISBN --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="isbn"
                            class="form-label"
                        >
                            ISBN
                        </label>

                        <input
                            type="text"
                            name="isbn"
                            id="isbn"
                            class="form-control @error('isbn') is-invalid @enderror"
                            value="{{ old('isbn', $buku->isbn) }}"
                            required
                        >

                        @error('isbn')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Judul --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="judul"
                            class="form-label"
                        >
                            Judul
                        </label>

                        <input
                            type="text"
                            name="judul"
                            id="judul"
                            class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul', $buku->judul) }}"
                            required
                        >

                        @error('judul')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Penulis --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="penulis"
                            class="form-label"
                        >
                            Penulis
                        </label>

                        <input
                            type="text"
                            name="penulis"
                            id="penulis"
                            class="form-control @error('penulis') is-invalid @enderror"
                            value="{{ old('penulis', $buku->penulis) }}"
                            required
                        >

                        @error('penulis')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Penerbit --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="penerbit"
                            class="form-label"
                        >
                            Penerbit
                        </label>

                        <input
                            type="text"
                            name="penerbit"
                            id="penerbit"
                            class="form-control @error('penerbit') is-invalid @enderror"
                            value="{{ old('penerbit', $buku->penerbit) }}"
                            required
                        >

                        @error('penerbit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Tahun Terbit --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="tahun_terbit"
                            class="form-label"
                        >
                            Tahun Terbit
                        </label>

                        <input
                            type="number"
                            name="tahun_terbit"
                            id="tahun_terbit"
                            class="form-control @error('tahun_terbit') is-invalid @enderror"
                            value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                            min="1900"
                            max="{{ date('Y') }}"
                            required
                        >

                        @error('tahun_terbit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Kategori --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="kategori_id"
                            class="form-label"
                        >
                            Kategori
                        </label>

                        <select
                            name="kategori_id"
                            id="kategori_id"
                            class="form-select @error('kategori_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- Pilih Kategori --
                            </option>

                            @foreach($kategoris as $kategori)

                                <option
                                    value="{{ $kategori->id }}"
                                    {{ old('kategori_id', $buku->kategori_id) == $kategori->id ? 'selected' : '' }}
                                >
                                    {{ $kategori->nama_kategori }}
                                </option>

                            @endforeach

                        </select>

                        @error('kategori_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Stok --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="stok"
                            class="form-label"
                        >
                            Stok
                        </label>

                        <input
                            type="number"
                            name="stok"
                            id="stok"
                            class="form-control @error('stok') is-invalid @enderror"
                            value="{{ old('stok', $buku->stok) }}"
                            min="0"
                            required
                        >

                        @error('stok')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Sinopsis --}}
                    <div class="col-12 mb-3">

                        <label
                            for="sinopsis"
                            class="form-label"
                        >
                            Sinopsis
                        </label>

                        <textarea
                            name="sinopsis"
                            id="sinopsis"
                            rows="5"
                            class="form-control @error('sinopsis') is-invalid @enderror"
                            placeholder="Masukkan sinopsis buku"
                        >{{ old('sinopsis', $buku->sinopsis) }}</textarea>

                        @error('sinopsis')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Tombol --}}
                <div class="d-flex gap-2">

                    <a
                        href="{{ route('buku.index') }}"
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