
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Mengecualikan endpoint SOAP dari pemeriksaan CSRF.
        $middleware->validateCsrfTokens(except: [
            'soap/*',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        // API selalu mengembalikan respons JSON.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, Throwable $exception) =>
                $request->is('api/*') || $request->expectsJson()
        );

        // Mengubah respons error Laravel menjadi RFC 7807.
        $exceptions->respond(function (
            Response $response,
            Throwable $exception,
            Request $request
        ) {

            // Jangan mengubah respons halaman web.
            if (!$request->is('api/*')) {
                return $response;
            }

            $status = $response->getStatusCode();

            // Respons sukses tidak perlu diubah.
            if ($status < 400) {
                return $response;
            }

            // Jangan mengubah respons yang sudah RFC 7807.
            if (str_contains(
                $response->headers->get('Content-Type', ''),
                'application/problem+json'
            )) {
                return $response;
            }

            $title = match ($status) {
                401 => 'Unauthorized',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                409 => 'Conflict',
                419 => 'Page Expired',
                422 => 'Unprocessable Content',
                429 => 'Too Many Requests',
                default => 'Internal Server Error',
            };

            $detail = match ($status) {
                401 => 'Autentikasi diperlukan untuk mengakses sumber daya ini.',
                403 => 'Anda tidak memiliki izin untuk mengakses sumber daya ini.',
                404 => 'Sumber daya yang diminta tidak ditemukan.',
                405 => 'Metode HTTP tidak diizinkan untuk endpoint ini.',
                409 => 'Terjadi konflik pada permintaan.',
                422 => 'Data yang dikirim tidak valid.',
                429 => 'Terlalu banyak permintaan.',
                default => 'Terjadi kesalahan pada server.',
            };

            $problem = [
                'type' => 'about:blank',
                'title' => $title,
                'status' => $status,
                'detail' => $detail,
            ];

            // Menyertakan rincian kesalahan validasi.
            if ($exception instanceof ValidationException) {
                $problem['errors'] = $exception->errors();
            }

            return response()->json(
                $problem,
                $status
            )->header(
                'Content-Type',
                'application/problem+json'
            );
        });

    })->create();
