<?php

namespace App\Services;

use App\Exceptions\RateLimitException;
use Carbon\Carbon;

class AppleStoreService extends BaseStoreService implements IStoreService
{
    /**
     * @param int $appId
     * @param string $receipt
     * @return array
     * @throws RateLimitException
     */
    public function verification(int $appId, string $receipt): array
    {
        $url = config('store.apple.host').config('store.apple.path.verification');
        $result = $this->sendVerificationRequest($url, $appId, $receipt);

        return [
            'result' => $result['result'],
            'expiration' => Carbon::parse($result['expiration'])->setTimezone(self::UTC),
        ];
    }
}