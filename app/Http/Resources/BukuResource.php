<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun_terbit' => $this->tahun_terbit,
            'kategori_id' => $this->kategori_id,
            'stok' => $this->stok,
            'sinopsis' => $this->sinopsis,

            'kategori' => $this->whenLoaded('kategori'),

            'peminjamans_count' => $this->whenCounted('peminjamans'),

            'links' => array_filter([
                [
                    'rel' => 'self',
                    'href' => route('api.v1.bukus.show', $this->id),
                    'method' => 'GET',
                ],

                $this->stok > 0 ? [
                    'rel' => 'pinjam_buku',
                    'href' => route('api.v1.peminjamans.store'),
                    'method' => 'POST',
                ] : null,
            ]),
        ];
    }
}
