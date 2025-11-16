<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface StudentRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?Model;

    public function export(Request $request): Builder;
}
