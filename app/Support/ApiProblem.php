<?php

namespace App\Support;

class ApiProblem
{
    public static function response(
        int $status,
        string $title,
        string $detail,
        array $extra = []
    ) {
        $body = [
            'type' => 'about:blank',
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
        ];

        $body = array_merge($body, $extra);

        return response()->json(
            $body,
            $status
        )->header(
            'Content-Type',
            'application/problem+json'
        );
    }
}
