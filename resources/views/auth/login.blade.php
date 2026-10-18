<x-layout title="Login Pustakawan">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h2 fw-bold mb-3">Login Pustakawan</h1>

                    <p class="text-secondary mb-4">
                        Masuk ke Sistem Informasi Perpustakaan Kampus Del.
                    </p>

                    <form action="{{ route('login.process') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="pustakawan@del.ac.id"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="form-check mb-4">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                value="1"
                                class="form-check-input"
                            >
                            <label for="remember" class="form-check-label">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-del w-100 py-2">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
