<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PexelsService
{
    private const ENDPOINT = 'https://api.pexels.com/v1/search';

    public function __construct(private readonly ?string $apiKey)
    {
    }

    /**
     * Apakah integrasi Pexels aktif (API key tersedia)?
     */
    public function enabled(): bool
    {
        return filled($this->apiKey);
    }

    /**
     * Cari foto di Pexels.
     *
     * @return array<int, array{id:int, alt:string, thumb:string, full:string, photographer:string, url:string}>
     */
    public function search(string $query, int $perPage = 12): array
    {
        if (! $this->enabled() || trim($query) === '') {
            return [];
        }

        $response = Http::withHeaders(['Authorization' => $this->apiKey])
            ->timeout(10)
            ->get(self::ENDPOINT, [
                'query' => $query,
                'per_page' => min(max($perPage, 1), 30),
                'orientation' => 'landscape',
            ]);

        if (! $response->successful()) {
            return [];
        }

        return collect($response->json('photos', []))
            ->map(fn (array $photo): array => [
                'id' => $photo['id'],
                'alt' => $photo['alt'] ?? '',
                'thumb' => $photo['src']['medium'] ?? $photo['src']['small'] ?? '',
                // Simpan ukuran besar (dibatasi lebar) sebagai thumbnail final.
                'full' => ($photo['src']['landscape'] ?? $photo['src']['large'] ?? $photo['src']['original'] ?? ''),
                'photographer' => $photo['photographer'] ?? '',
                'url' => $photo['url'] ?? '',
            ])
            ->filter(fn (array $p): bool => $p['full'] !== '')
            ->values()
            ->all();
    }
}
