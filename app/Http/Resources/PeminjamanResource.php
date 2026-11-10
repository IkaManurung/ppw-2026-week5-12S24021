<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode_pinjam' => $this->kode_pinjam,
            'buku_id' => $this->buku_id,
            'nim_peminjam' => $this->nim_peminjam,
            'nama_peminjam' => $this->nama_peminjam,
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'tanggal_jatuh_tempo' => $this->tanggal_jatuh_tempo,
            'tanggal_kembali' => $this->tanggal_kembali,
            'status' => $this->status,

            'buku' => $this->whenLoaded('buku'),

            'links' => array_values(array_filter([
                [
                    'rel' => 'self',
                    'href' => route(
                        'api.v1.peminjamans.index'
                    ),
                    'method' => 'GET',
                ],

                $this->status === 'dipinjam' ? [
                    'rel' => 'kembalikan_buku',
                    'href' => route(
                        'api.v1.peminjamans.kembalikan',
                        $this->id
                    ),
                    'method' => 'PATCH',
                ] : null,
            ])),
        ];
    }
}
