<?php

namespace App\Services;

use App\Exceptions\RateLimitException;

interface IStoreService
{
    /**
     * @param int $appId
     * @param string $receipt
     * @return array
     * @throws RateLimitException
     */
    function verification(int $appId, string $receipt): array;
}