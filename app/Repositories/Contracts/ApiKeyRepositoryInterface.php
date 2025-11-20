<?php

namespace App\Repositories\Contracts;

interface ApiKeyRepositoryInterface
{
    public function isValidKey(string $key, string $platform): bool;

    public function getPlatformByKey(string $key): ?string;
}
