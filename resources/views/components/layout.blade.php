<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'SIPUS-Del' }} — Institut Teknologi Del</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        :root {
            --del-purple: #4C1D95;
            --del-purple-dark: #3B0764;
        }

        body {
            background-color: #f8f9fa;
        }

        .navbar-del {
            background-color: var(--del-purple);
        }

        .btn-del {
            background-color: var(--del-purple);
            color: #ffffff;
        }

        .btn-del:hover {
            background-color: var(--del-purple-dark);
            color: #ffffff;
        }

        .brand-title {
            font-weight: 700;
        }

        .table thead {
            background-color: var(--del-purple);
            color: white;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-del shadow-sm mb-4">
        <div class="container">

            <a
                class="navbar-brand brand-title"
                href="{{ route('buku.index') }}"
            >
                SIPUS-Del
            </a>

            <div class="navbar-nav ms-auto">

                <a
                    class="nav-link"
                    href="{{ route('buku.index') }}"
                >
                    Buku
                </a>

                <a
                    class="nav-link"
                    href="{{ route('kategori.index') }}"
                >
                    Kategori
                </a>

            </div>

        </div>
    </nav>

    <div class="container pb-5">

        {{-- Flash Message --}}
        @if(session('success'))
            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >
                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>
            </div>
        @endif


        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">

                <strong>Terdapat kesalahan:</strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif


        {{-- Isi halaman --}}
        {{ $slot }}

    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>

</html>