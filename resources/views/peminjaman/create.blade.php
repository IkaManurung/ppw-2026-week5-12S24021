
<x-layout title="Form Peminjaman">

    <h1 class="fw-bold mb-4">Form Peminjaman Buku</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="buku_id" class="form-label">Pilih Buku</label>
                    <select name="buku_id" id="buku_id" class="form-select" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($bukus as $buku)
                            <option
                                value="{{ $buku->id }}"
                                {{ old('buku_id', request('buku_id')) == $buku->id ? 'selected' : '' }}
                                {{ $buku->stok < 1 ? 'disabled' : '' }}
                            >
                                {{ $buku->judul }} (Stok: {{ $buku->stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('buku_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nim_peminjam" class="form-label">NIM Peminjam</label>
                    <input
                        type="text"
                        name="nim_peminjam"
                        id="nim_peminjam"
                        class="form-control"
                        value="{{ old('nim_peminjam') }}"
                        required
                    >
                    @error('nim_peminjam')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
                    <input
                        type="text"
                        name="nama_peminjam"
                        id="nama_peminjam"
                        class="form-control"
                        value="{{ old('nama_peminjam') }}"
                        required
                    >
                    @error('nama_peminjam')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                    <input
                        type="date"
                        name="tanggal_pinjam"
                        id="tanggal_pinjam"
                        class="form-control"
                        value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                        required
                    >
                    @error('tanggal_pinjam')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                    <input
                        type="date"
                        name="tanggal_jatuh_tempo"
                        id="tanggal_jatuh_tempo"
                        class="form-control"
                        value="{{ old('tanggal_jatuh_tempo') }}"
                        required
                    >
                    @error('tanggal_jatuh_tempo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-del">
                    Simpan Peminjaman
                </button>

                <a href="{{ route('buku.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>

        </div>
    </div>

</x-layout>
