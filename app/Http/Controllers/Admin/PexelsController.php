<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PexelsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PexelsController extends Controller
{
    public function __construct(private readonly PexelsService $pexels)
    {
    }

    /**
     * Cari foto Pexels untuk pemilih thumbnail di dasbor.
     */
    public function search(Request $request): JsonResponse
    {
        if (! $this->pexels->enabled()) {
            return response()->json([
                'enabled' => false,
                'message' => 'Fitur Pexels nonaktif. Setel PEXELS_API_KEY di .env untuk mengaktifkannya.',
                'photos' => [],
            ]);
        }

        $validated = $request->validate([
            'q' => ['required', 'string', 'max:100'],
        ]);

        return response()->json([
            'enabled' => true,
            'photos' => $this->pexels->search($validated['q']),
        ]);
    }
}
