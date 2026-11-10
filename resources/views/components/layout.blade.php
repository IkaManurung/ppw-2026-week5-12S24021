<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'SIPUS-Del' }} — Institut Teknologi Del</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-del {
            background-color: #4C1D95;
        }

        .btn-del {
            background-color: #4C1D95;
            color: #ffffff;
        }

        .btn-del:hover {
            background-color: #3B0764;
            color: #ffffff;
        }

        .btn-logout {
            border: 1px solid #ffffff;
            color: #ffffff;
            background-color: transparent;
        }

        .btn-logout:hover {
            background-color: #ffffff;
            color: #4C1D95;
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark navbar-del shadow-sm mb-4">
        <div class="container">

            <a class="navbar-brand fw-bold" href="{{ route('buku.index') }}">
                SIPUS-Del
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu"
                aria-controls="navbarMenu"
                aria-expanded="false"
                aria-label="Buka navigasi"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">

                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('buku.index') }}">
                                Buku
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('kategori.index') }}">
                                Kategori
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('peminjaman.index') }}">
                                Peminjaman
                            </a>
                        </li>
                    @endauth
                </ul>

                @auth
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-white">
                            {{ Auth::user()->name }}
                        </span>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button type="submit" class="btn btn-logout btn-sm">
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth

            </div>
        </div>
    </nav>

    <main class="container pb-5">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"
                ></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"
                ></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
