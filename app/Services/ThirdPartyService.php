<?php

namespace App\Services;

use Http;

class ThirdPartyService
{
    public function sendSubscriptionStatusEvent(int $appId, int $deviceId, string $eventInfo)
    {
        $url = config('third-party.host').config('third-party.path.status');

        $response = Http::retry(3, 100)
            ->timeout(5)
            ->post(
                $url,
                [
                    'app_id' => $appId,
                    'deviceId_id' => $deviceId,
                    'event_info' => $eventInfo,
                ]
            );

        return json_decode($response->body(), true);
    }
}