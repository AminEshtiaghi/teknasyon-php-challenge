<?php

namespace App\Services;

use App\Exceptions\RateLimitException;
use Http;
use Illuminate\Support\Str;

class BaseStoreService
{
    const UTC = 'UTC';
    const UTCm6 = 'Pacific/Easter';

    /**
     * @param string $endpoint
     * @param int $appId
     * @param string $receipt
     * @return array
     * @throws RateLimitException
     */
    protected function sendVerificationRequest(string $endpoint, int $appId, string $receipt): array
    {
        $response = Http::retry(3, 100)
            ->timeout(5)
            ->withHeaders(
                [
                    'auth' => $this->getAppUsername($appId).':'.$this->getAppPassword($appId),
                ]
            )
            ->post(
                $endpoint,
                [
                    'receipt' => $receipt,
                ]
            );

        if ($response->failed()) {
            if ($response->status() === 429) {

                throw new RateLimitException('Rate limit Exception!');

            }
        }

        return json_decode($response->body(), true);
    }

    private function getAppUsername(int $appId): string
    {
        return Str::uuid();
    }

    private function getAppPassword(int $appId): string
    {
        return Str::uuid();
    }
}