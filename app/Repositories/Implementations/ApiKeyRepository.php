<?php

namespace App\Repositories\Implementations;

use App\Models\ApiKey;
use App\Repositories\Contracts\ApiKeyRepositoryInterface;

class ApiKeyRepository implements ApiKeyRepositoryInterface
{
    public function isValidKey(string $key, string $platform): bool
    {
        return ApiKey::where('key', $key)
            ->where('platform', $platform)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    public function getPlatformByKey(string $key): ?string
    {
        $apiKey = ApiKey::where('key', $key)->first();

        return $apiKey?->platform;
    }

    public function findById(int $id): ?ApiKey
    {
        return ApiKey::find($id);
    }

    public function getAllActiveKeys(): array
    {
        return ApiKey::where('is_active', true)->get()->toArray();
    }
}
